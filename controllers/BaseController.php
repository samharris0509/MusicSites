<?php
class BaseController {
    public function render($view, $data = []) {
        extract($data);
        include 'views/partials/header.php';
        include "views/$view.php";
        include 'views/partials/footer.php';
    }
}
?>
