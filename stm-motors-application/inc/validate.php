<?php


function stmMRAValidateNumber($param, $request, $key) {
    return is_numeric( $param );
}