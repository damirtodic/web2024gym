<?php

/**
 * @OA\Info(
 *   title="API",
 *   description="Web programming GYM API",
 *   version="1.0",
 *   @OA\Contact(
 *     email="damir.todic@stu.ibu.edu.ba",
 *     name="Damir Todic"
 *   )
 * ),
 * @OA\OpenApi(
 *   @OA\Server(
 *       url=BASE_URL
 *   )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authentication"
 * )
 */
