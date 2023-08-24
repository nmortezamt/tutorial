<?php

namespace Tutorial\Ticket\Repositories;

use Tutorial\Ticket\Models\Ticket;

class TicketRepo
{
    private $query;

    public function __construct()
    {
        $this->query = Ticket::query();
    }

    public function paginateAll($userId = null)
    {
        if(! is_null($userId)) $this->query->where('user_id',$userId);
        return $this->query->latest()->paginate();
    }

    public function joinUsers()
    {
        $this->query->join('users','tickets.user_id','users.id')
        ->select('tickets.*','users.email','users.name');
        return $this;
    }

    public function searchEmail($email)
    {
        if(! is_null($email)){
            $this->query->where('email','LIKE',"%{$email}%");
        }
        return $this;
    }

    public function searchTitle($title)
    {
        if(! is_null($title)){
            $this->query->where('title','LIKE',"%{$title}%");
        }

        return $this;
    }

    public function searchName($name)
    {

        if(! is_null($name)){
            $this->query->where('name','LIKE',"%{$name}%");
        }
        return $this;
    }

    public function searchDate($date)
    {
        if(! is_null($date)){
            $this->query->whereDate('tickets.created_at',$date);
        }
        return $this;
    }

    public function searchStatus($status)
    {
        if(! is_null($status)){
            $this->query->where('tickets.status',$status);
        }
        return $this;
    }

    public function store($data)
    {
        return Ticket::query()->create([
            'user_id'=> auth()->id(),
            'title'=>$data->title
        ]);
    }

    public function findWithReplies($id)
    {
        return Ticket::query()->with('replies')->findOrFail($id);
    }

    public function changeStatus($id,string $status)
    {
        return Ticket::query()->where('id',$id)
        ->update(['status'=>$status]);
    }

    public function delete($id)
    {
        $replyRepo = new ReplyRepo();
        $replyRepo->delete($id);
        Ticket::query()->where('id',$id)->delete();
    }

}
