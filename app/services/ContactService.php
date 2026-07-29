<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\ContactRepository;

class ContactService
{
    public function __construct(
        private ContactRepository $contactRepository
    ) {
    }

    public function createContact(array $data): Contact
    {
        return $this->contactRepository->create($data);
    }
}
