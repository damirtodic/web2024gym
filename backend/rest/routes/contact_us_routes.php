<?php
require_once __DIR__ . '/../services/ContactUsService.class.php';

Flight::set('contact_us_service', new ContactUsService());
    /**
     * @OA\POST(
     *      path="/contact",
     *      tags={"CONTACT"},
     *      summary="CONTACT US",
  *      @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  required={"name", "email", "message"},
 *                  @OA\Property(
 *                      property="name",
 *                      type="string",
 *                      description="Sender's name"
 *                  ),
 *                  @OA\Property(
 *                      property="email",
 *                      type="string",
 *                      format="email",
 *                      description="Sender's email address"
 *                  ),
 *                  @OA\Property(
 *                      property="message",
 *                      type="string",
 *                      description="Message content"
 *                  )
 *              )
 *          )
 *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Message sent successfully"
     *      ),

     * )
     */
Flight::route('POST /contact', function(){
    $payload = Flight::request()->data;

    if (empty($payload['name'])) {
        Flight::json(['error' => 'Name is missing'], 400);
        return;
    }
    if (empty($payload['email'])) {
        Flight::json(['error' => 'Email is missing'], 400);
        return;
    }
    if (empty($payload['message'])) {
        Flight::json(['error' => 'Message is missing'], 400);
        return;
    }

    if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
        Flight::json(['error' => 'Invalid email format'], 400);
        return;
    }

    $contact_us_service = Flight::get('contact_us_service');
    $result = $contact_us_service->addContactMessage($payload['name'], $payload['email'], $payload['message']);

    if ($result) {
        Flight::json(['message' => 'Message sent successfully']);
    } else {
        Flight::json(['error' => 'Failed to send message'], 500);
    }
});

?>
