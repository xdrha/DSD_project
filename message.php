<?php
class Message
{
    public $receiver_id;
    public $time;
    public $sender_name;
    public $message;
    public $delivered;
    public $receiver_name;
    public $uuid;
    public $html;

    function get_message()
    {
        return $this->message;
    }

    function get_time()
    {
        return $this->time;
    }

    function get_sender_name()
    {
        return $this->sender_name;
    }

    function get_delivered()
    {
        return $this->delivered;
    }

    function build_html($name)
    {
        if (strcmp($this->sender_name, $name) == 0) {
            $this->html = "<li class='me'><p>" . $this->message . "<button class='btn' onclick='window.message_id_to_delete =\"$this->uuid\";'><i class='fa fa-trash' data-toggle='modal' data-target='#remove_dialog'></i></button></p><small>";
        } else {
            $this->html = "<li><h4>" . $this->sender_name . "<button class='btn' onclick='window.message_id_to_delete =\"$this->uuid\";'><i class='fa fa-trash' data-toggle='modal' data-target='#remove_dialog'></i></button></h4><p>" . $this->message . "</p><small>";
        }

        if ($this->delivered == 1) {
            $this->html .= "<span>&#10003;&#10003;</span>";
        }
        $this->html .= $this->time . "</small></li>";
    }

    function get_html()
    {
        return $this->html;
    }
}
