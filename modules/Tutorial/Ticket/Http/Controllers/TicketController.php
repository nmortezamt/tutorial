<?php

namespace Tutorial\Ticket\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\Ticket\Http\Requests\ReplyRequest;
use Tutorial\Ticket\Http\Requests\TicketRequest;
use Tutorial\Ticket\Models\Ticket;
use Tutorial\Ticket\Repositories\TicketRepo;
use Tutorial\Ticket\Services\ReplyService;

use function Tutorial\Common\dateFromJalali;
use function Tutorial\Common\newFeedbacks;

class TicketController extends Controller
{
    private $ticketRepo;

    public function __construct(TicketRepo $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }

    public function index(Request $request)
    {
        if(auth()->user()->can(Permission::PERMISSION_MANAGE_TICKETS)){
            $tickets = $this->ticketRepo->joinUsers()
            ->searchTitle($request->title)
            ->searchEmail($request->email)
            ->searchName($request->name)
            ->searchDate(dateFromJalali($request->date))
            ->searchStatus($request->status)
            ->paginateAll();
        }else{
            $tickets = $this->ticketRepo->paginateAll(auth()->id());
        }
        return view('Ticket::index',compact('tickets'));
    }

    public function create()
    {
        return view('Ticket::create');
    }

    public function store(TicketRequest $request)
    {
        $ticket = $this->ticketRepo->store($request);
        ReplyService::store($ticket,$request->body,$request->attachment ? $request->file('attachment') : null);
        newFeedbacks();
        return redirect()->route('tickets.index');
    }

    public function show($ticketId)
    {
        $ticket = $this->ticketRepo->findWithReplies($ticketId);
        $this->authorize('show',$ticket);
        return view('Ticket::show',compact('ticket'));
    }

    public function reply(Ticket $ticket,ReplyRequest $request)
    {
        $this->authorize('show',$ticket);
        ReplyService::store($ticket,$request->body,$request->attachment ? $request->file('attachment') : null);
        newFeedbacks();
        return redirect()->route('tickets.show',$ticket->id);
    }

    public function close(Ticket $ticket)
    {
        $this->authorize('show',$ticket);
        $this->ticketRepo->changeStatus($ticket->id,Ticket::STATUS_CLOSE);
        return AjaxResponses::SuccessResponse();
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete',Ticket::class);
        $this->ticketRepo->delete($ticket->id);
        return AjaxResponses::SuccessResponse();
    }

}
