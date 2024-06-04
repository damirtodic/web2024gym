<?php
require_once __DIR__ . "/../dao/ContactUsDao.class.php";

class ContactUsService {
    private $contactUsDao;

    public function __construct() {
        $this->contactUsDao = new ContactUsDao();
    }

    public function addContactMessage($name, $email, $message) {
        return $this->contactUsDao->addContactMessage($name, $email, $message);
    }
}
?>
