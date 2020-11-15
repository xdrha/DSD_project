<?php
class Branch
{
  public $id;
  public $ip;
  public $address;
  public $online;
  public $queue;
  public $html;

  function build_html()
  {
    if (strcmp($this->ip, "localhost") != 0) {
      $this->html = "<li class='treeview'>
            <a href='#'>
            <div class='search_class form' style='margin: 0; height: 50px;'>
                <img src='../dist/img/branch1.png' alt='User Image' width='50' height='50' style='padding: 5px;'>
                <span class='branch-text'>" . $this->address . "&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;" . $this->ip . "</span>
                <p class='branch-p'>
                <i class='fa fa-circle text-";
      if ($this->online == TRUE) {
        $this->html .= "success";
      } else {
        $this->html .= "red";
      }
      $this->html .= "'></i></p>
              </div>
              <span class='pull-right-container'>
                <i class='fa fa-angle-left pull-right'></i>
              </span>
            </a>
            <ul class='treeview-menu'>
              <li><a href='#branch_remove_dialog' data-toggle='modal' onclick='window.branch_id_to_delete =\"$this->id\";'>Remove</a></li>
            </ul>
          </li>";
    }
  }

  function get_html()
  {
    return $this->html;
  }
}
