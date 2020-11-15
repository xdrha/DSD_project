<?php
class Availability
{
    public $branch_address;
    public $count = "-";
    public $html;
    public $online = FALSE;

    function build_html($ip)
    {
        $this->html = "<tr style='border-top: 1px solid #ffffff; border-bottom: 1px solid #ffffff;'>" .
            "<td width='66.66%' style='text-align: center; border-left: 1px solid #ffffff; border-right: 1px solid #ffffff; color: #ffffff;'>" .
            $this->branch_address . "</td>" .
            "<td width='23.33%' style='text-align: center; color: #ffffff;'>";

        if ($ip == "localhost") {
            $this->html .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }

        $this->html .= $this->count;

        if ($ip == "localhost") {
            $this->html .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<svg data-toggle='modal' data-target='#bike_count_dialog' onclick='set_count(" . $this->count . ")' width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-pencil-fill' fill='#ffffff' xmlns=http://www.w3.org/2000/svg'>" .
                "<path fill-rule='evenodd' d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293" .
                " 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 " .
                "0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 " .
                "0 0 0 .168-.11l.178-.178z'/></svg>";
        }

        $this->html .= "</td>" .
            "<td style='text-align: center; border-left: 1px solid #ffffff; border-right: 1px solid #ffffff;'>";

        if ($this->count != "0" && $this->online) {
            $this->html .= "<img src='../dist/img/ok.png' alt='User Image' width='80%' height='auto'></td></tr>";
        } else {
            if ($this->count == "0" && $this->online) {
                $this->html .= "<img src='../dist/img/fail.png' alt='User Image' width='80%' height='auto'></td></tr>";
            } else {
                $this->html .= "<img src='../dist/img/maybe.png' alt='User Image' width='80%' height='auto'></td></tr>";
            }
        }
    }

    function get_html()
    {
        return $this->html;
    }
}
