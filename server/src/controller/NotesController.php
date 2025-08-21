<?php

namespace Controller;

use Helper\Validator;

class NotesController {
    public function __construct(private $gateway) {}

    public function getAllNotes(): void {
        echo json_encode($this->gateway->getAll());
    }
}