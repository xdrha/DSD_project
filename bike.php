<?php
class Bike
{
    public $price;
    public $brand;
    public $branch_ip;
    public $model;
    public $uuid;
    public $html;

    function build_html($ip)
    {
        $bike_name = $this->brand . " " . $this->model;
        $this->html = "<li class= 'pointer ";
        if (strcmp($this->branch_ip, $ip) == 0) {
            $this->html .= "me";
        }
        $this->html .= "' onclick='window.bike_name= \"$bike_name\"; window.bike_price=\"$this->price\"; window.bike_id =\"$this->uuid\"; check_bike_availability(window.bike_id)'><h4>" . $bike_name .
            "<button class='btn edit' onclick='window.bike_id =\"$this->uuid\"; window.bike_brand=\"$this->brand\"; window.bike_model=\"$this->model\"; window.bike_price=\"$this->price\"; set_bike_data();'><i class='glyphicon glyphicon-pencil' data-toggle='modal' data-target='#edit_bike_dialog'></i></button>" .
            "<button class='btn' onclick='window.bike_id =\"$this->uuid\";'><i class='fa fa-trash' data-toggle='modal' data-target='#bike_remove_dialog'></i></button></h4><small>";

        $this->html .= $this->price . " &euro;</small></li>";
    }

    function get_html()
    {
        return $this->html;
    }
}
