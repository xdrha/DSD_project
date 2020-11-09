<?php
class Message
{
    public $time;
    public $sender_name;
    public $sender_ip;
    public $message;
    public $uuid;
    public $html;

    function build_html($name)
    {
        error_log("jeeeeeeeeeah " . $name . " juuuuuuuuuuuuuj " . $this->sender_name);
        if (strcmp($this->sender_name, $name) == 0) {
            $this->html = "<li class='me'><p>" . $this->message . "<button class='btn' onclick='window.message_id_to_delete =\"$this->uuid\";'><i class='fa fa-trash' data-toggle='modal' data-target='#message_remove_dialog'></i></button></p><small>";
        } else {
            $this->html = "<li><h4>" . $this->sender_name . "<button class='btn' onclick='window.message_id_to_delete =\"$this->uuid\";'><i class='fa fa-trash' data-toggle='modal' data-target='#message_remove_dialog'></i></button></h4><p>" . $this->message . "</p><small>";
        }

        $this->html .= $this->time . "</small></li>";
    }

    function get_html()
    {
        return $this->html;
    }
}
