<?php
foreach ($this->objects as $object) {
    echo shtml_tag("tr");
    foreach ($this->columns as $column) {
        echo shtml_tag("td");
        echo is_numeric($column) ? '' : $object->$column;
        echo shtml_untag("td");
    }
    echo shtml_untag("tr");
}
