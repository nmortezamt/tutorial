<?php

namespace Tutorial\Discount\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\Course\Models\Course;
use Tutorial\Course\Repositories\CourseRepo;
use Tutorial\Discount\Http\Requests\DiscountRequest;
use Tutorial\Discount\Models\Discount;
use Tutorial\Discount\Repositories\DiscountRepo;
use Tutorial\Discount\Services\DiscountService;

use function Tutorial\Common\newFeedbacks;

class DiscountController extends Controller
{
    private $discountRepo;

    public function __construct(DiscountRepo $discountRepo)
    {
        $this->discountRepo = $discountRepo;
    }

    public function index(CourseRepo $courseRepo)
    {
        $this->authorize('manage', Discount::class);
        $courses = $courseRepo->getAll(Course::CONFIRMATION_STATUS_ACCEPTED);
        $discounts = $this->discountRepo->paginate();
        return view('Discount::index', compact('courses', 'discounts'));
    }

    public function store(DiscountRequest $request)
    {
        $this->authorize('manage', Discount::class);
        $this->discountRepo->store($request);
        newFeedbacks();
        return back();
    }

    public function destroy($discountId)
    {
        $this->authorize('manage', Discount::class);
        $this->discountRepo->delete($discountId);
        return AjaxResponses::SuccessResponse();
    }

    public function edit($discountId, CourseRepo $courseRepo)
    {
        $this->authorize('manage', Discount::class);
        $discount = $this->discountRepo->findById($discountId);
        $courses = $courseRepo->getAll(Course::CONFIRMATION_STATUS_ACCEPTED);
        return view('Discount::edit', compact('discount', 'courses'));
    }

    public function update($discountId, DiscountRequest $request)
    {
        $this->authorize('manage', Discount::class);
        $this->discountRepo->update($discountId, $request);
        newFeedbacks();
        return redirect(route('discounts.index'));
    }

    public function check($code, Course $course)
    {
        $discount = $this->discountRepo->getValidDiscountByCode($course->id, $code);
        if ($discount) {
            $discountPercent = $discount->percent;
            $discountAmount = DiscountService::calculateDiscountAmount($course->finalPrice(),$discount->percent);
            $payableAmount = $course->finalPrice() - $discountAmount;
            $response = [
                'status' => 'valid',
                'payableAmount' => $payableAmount,
                'discountPercent' => $discountPercent,
                'discountAmount' => $discountAmount,
            ];
            return response()->json($response);
        }

        return response()->json(['status' => 'invalid'])->setStatusCode(422);
    }
}
