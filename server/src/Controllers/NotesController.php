<?php

namespace Controllers;

use Services\NotesService;

class NotesController {
    public function __construct(private NotesService $service) {}

    public function getAllNotes(): void {
        echo json_encode($this->service->getAllNotes());
    }
}