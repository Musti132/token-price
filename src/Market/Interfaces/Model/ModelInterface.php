<?php
namespace TokenPrice\Market\Interfaces\Model;

interface ModelInterface {

    /**
     * Filter out data from Market API response.
     */
    public function filter();
}
?>