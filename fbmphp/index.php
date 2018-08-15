<?php

$verify_token = ""; // Verify token
$token = ""; // Page token

if (file_exists(__DIR__ . '/config.php')) {
    $config = include __DIR__ . '/config.php';
    $verify_token = $config['verify_token'];
    $token = $config['token'];
}

require_once(dirname(__FILE__) . '/vendor/autoload.php');

use pimax\FbBotApp;
use pimax\Menu\MenuItem;
use pimax\Menu\LocalizedMenu;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;
use pimax\Messages\AccountLink;
use pimax\Messages\ImageMessage;
use pimax\Messages\QuickReply;
use pimax\Messages\QuickReplyButton;
use pimax\Messages\SenderAction;


// Make Bot Instance
$bot = new FbBotApp($token);

if (!empty($_REQUEST['local'])) {

    $message = new ImageMessage(1585388421775947, dirname(__FILE__).'/fb4d_logo-2x.png');

    $message_data = $message->getData();
    $message_data['message']['attachment']['payload']['url'] = 'fb4d_logo-2x.png';

    echo '<pre>', print_r($message->getData()), '</pre>';

    $res = $bot->send($message);

    echo '<pre>', print_r($res), '</pre>';
}

// Receive something
if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {

    // Webhook setup request
    echo $_REQUEST['hub_challenge'];
} else {

    // Other event
    $data = json_decode(file_get_contents("php://input"), true, 512, JSON_BIGINT_AS_STRING);
    if (!empty($data['entry'][0]['messaging'])) {

        foreach ($data['entry'][0]['messaging'] as $message) {

            // Skipping delivery messages
            if (!empty($message['delivery'])) {
                continue;
            }

            // skip the echo of my own messages
            if (($message['message']['is_echo'] == "true")) {
                continue;
            }

            $command = "";

            // When bot receive message from user
            if (!empty($message['message'])) {
                $command = trim($message['message']['text']);

            // When bot receive button click from user
            } else if (!empty($message['postback'])) {
                $text = trim($message['postback']['payload']);
                //echo $command."<br>";
                //die(var_dump($text));
                $bot->send(new Message($message['sender']['id'], $text));
                $command = $text;
                //continue;
            }

            //die(var_dump($command));

            // Handle command
            switch ($command) {

                // When bot receive "text"
                case 'text':
                    $bot->send(new Message($message['sender']['id'], 'This is a simple text message. 👋😼😼👂🦁🐟🐟💮🥩🍕'));
                    break;

                // When bot receive "image"
                case 'image':
                    $bot->send(new ImageMessage($message['sender']['id'], 'https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-9/31298447_1672571726157254_6833703803954276108_n.jpg?_nc_cat=0&oh=6b6c35452ce00d158d26e2a7058e91f1&oe=5C0FA357'));
                    break;

                // When bot receive "local image"
                //case 'local image':
                    //$bot->send(new ImageMessage($message['sender']['id'], dirname(__FILE__).'/fb_logo.png'));
                    //break;

                // When bot receive "profile"
                case 'profile':
                    $user = $bot->userProfile($message['sender']['id']);
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_GENERIC,
                        [
                            'elements' => [
                                new MessageElement($user->getFirstName()." ".$user->getLastName(), " ", $user->getPicture())
                            ]
                        ],
                        [ 
                        	new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'QR button','PAYLOAD') 
                        ]
                    ));
                    break;

                case 'Үндсэн цэс':
                    $bot->send(new ImageMessage($message['sender']['id'], 'https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-9/31298447_1672571726157254_6833703803954276108_n.jpg?_nc_cat=0&oh=6b6c35452ce00d158d26e2a7058e91f1&oe=5C0FA357'));
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_BUTTON,
                        [
                            'text' => 'Бидний үйлчилгээ',
                            'buttons' => [
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'Бүтээгдэхүүн', 'Бүтээгдэхүүн'),
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'Нөхөн төлбөр', 'Нөхөн төлбөр'),
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'Ажилтантай чатлах', 'Ажилтантай чатлах')
                            ]
                        ],
                        [ 
                            //new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Үндсэн цэс','Үндсэн цэс'),
                            new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Бусад цэс','Бусад цэс')
                        ]
                    ));
                    break;

                case 'Бүтээгдэхүүн':
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_GENERIC,
                        [
                            'elements' => [
                                new MessageElement("Үлэмж багц даатгал (ТХ, ЖХАЖД)", "Тээврийн хэрэгсэл, Жолоочийн хариуцлагын албан журмын даатгал", "https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-9/38797481_1492509740894537_995936438438592512_n.jpg?_nc_cat=0&oh=5819599db9e2ed6dad454a587c355592&oe=5C06C0FB", [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Даатгуулах'),
                                    new MessageButton(MessageButton::TYPE_WEB, 'Холбоос', 'https://www.practical.mn/313')
                                ]),

                                new MessageElement("Энх Ирээдүй (ЭМ, ГОД)", "Эрүүл мэнд, Гэнтийн ослын даатгал", "https://www.practical.mn/upload/homepro/5_9749664.jpg", [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Даатгуулах'),
                                    new MessageButton(MessageButton::TYPE_WEB, 'Холбоос', 'https://www.practical.mn/183')
                                ]),

                                new MessageElement("Эд хөрөнгө", "Эд хөрөнгө", "https://www.practical.mn/upload/nemelt_zurag/131_2918811.jpg", [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Даатгуулах'),
                                    new MessageButton(MessageButton::TYPE_WEB, 'Холбоос', 'https://www.practical.mn/131')
                                ]),

                                new MessageElement("Санхүү", "Санхүү", "https://www.practical.mn/upload/nemelt_zurag/151_4314352.jpg", [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Даатгуулах'),
                                    new MessageButton(MessageButton::TYPE_WEB, 'Холбоос', 'https://www.practical.mn/39')
                                ]),

                                new MessageElement("Хариуцлага", "Хариуцлага", "https://www.practical.mn/upload/nemelt_zurag/125_9223603.jpg", [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Даатгуулах'),
                                    new MessageButton(MessageButton::TYPE_WEB, 'Холбоос', 'https://www.practical.mn/37')
                                ]),

                                new MessageElement("Гадаад, дотоод", "Гадаад, дотоод", "https://www.practical.mn/upload/homepro/2_3426025.jpg", [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Даатгуулах'),
                                    new MessageButton(MessageButton::TYPE_WEB, 'Холбоос', 'https://www.practical.mn/73')
                                ])

                            ]
                        ],
                        [ 
                            new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Үндсэн цэс','Үндсэн цэс'),
                            new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Бусад цэс','Бусад цэс'),
                        ]
                    ));
                    break;

                case 'Нөхөн төлбөр':
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_GENERIC,
                        [
                            'elements' => [
                                new MessageElement("Нөхөн төлбөрийн маягтууд", "", "", [
                                    new MessageButton(MessageButton::TYPE_WEB, 'Энх ирээдүй' , 'http://www.practical.mn/mayagt1/'),
                                    new MessageButton(MessageButton::TYPE_WEB, 'Гадаадад зорчигч', 'http://www.practical.mn/mayagt2/')
                                ]),

                                new MessageElement("Нөхөн төлбөрийн зөвлөгөө", "Нөхөн төлбөрийн зөвлөгөө, бүрдүүлэх бичиг баримт", "", [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Зөвлөгөө'),
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Бүрдүүлэх бичиг баримт')
                                ])

                            ]
                        ],
                        [ 
                            new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Үндсэн цэс','Үндсэн цэс'),
                            new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Бусад цэс','Бусад цэс'),
                        ]
                    ));
                    break;



                
                // When bot receive "quick reply"
                case 'Бусад цэс':
                    $bot->send(new QuickReply($message['sender']['id'], 'Бусад цэс', 
                            [
                                new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'image', 'image'),
                                new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'list', 'list'),
                                new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Байршил илгээх', 'Байршил илгээх'),
                            ]
                    ));
                    break;
                    
                // When bot receive "location"
                case 'Байршил илгээх':
                    $bot->send(new QuickReply($message['sender']['id'], 'Байршил илгээх', 
                            [
                                new QuickReplyButton(QuickReplyButton::TYPE_LOCATION),
                            ]
                    ));
                    break;
                    
                    
                // When bot receive "list"
                case 'list':
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_LIST,
                        [
                            'elements' => [
                                new MessageElement(
                                    'Classic T-Shirt Collection', // title
                                    'See all our colors', // subtitle
                                    'http://bit.ly/2pYCuIB', // image_url
                                    [ // buttons
                                        new MessageButton(MessageButton::TYPE_POSTBACK, // type
                                            'View', // title
                                            'POSTBACK' // postback value
                                        )
                                    ]
                                ),
                                new MessageElement(
                                    'Classic White T-Shirt', // title
                                    '100% Cotton, 200% Comfortable', // subtitle
                                    'http://bit.ly/2pb1hqh', // image_url
                                    [ // buttons
                                        new MessageButton(MessageButton::TYPE_WEB, // type
                                            'View', // title
                                            'https://google.com' // url
                                        )
                                    ]
                                )
                            ],
                            'buttons' => [
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'First button', 'PAYLOAD 1')
                            ]
                        ],
                        [
                            new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'QR button','PAYLOAD')
                        ]
                    ));
                    break;

                // When bot receive "receipt"
                case 'receipt':
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_RECEIPT,
                        [
                            'recipient_name' => 'Fox Brown',
                            'order_number' => rand(10000, 99999),
                            'currency' => 'USD',
                            'payment_method' => 'VISA',
                            'order_url' => 'http://facebook.com',
                            'timestamp' => time(),
                            'elements' => [
                                new MessageReceiptElement("First item", "Item description", "", 1, 300, "USD"),
                                new MessageReceiptElement("Second item", "Item description", "", 2, 200, "USD"),
                                new MessageReceiptElement("Third item", "Item description", "", 3, 1800, "USD"),
                            ],
                            'address' => new Address([
                                'country' => 'US',
                                'state' => 'CA',
                                'postal_code' => 94025,
                                'city' => 'Menlo Park',
                                'street_1' => '1 Hacker Way',
                                'street_2' => ''
                            ]),
                            'summary' => new Summary([
                                'subtotal' => 2300,
                                'shipping_cost' => 150,
                                'total_tax' => 50,
                                'total_cost' => 2500,
                            ]),
                            'adjustments' => [
                                new Adjustment([
                                    'name' => 'New Customer Discount',
                                    'amount' => 20
                                ]),

                                new Adjustment([
                                    'name' => '$10 Off Coupon',
                                    'amount' => 10
                                ])
                            ]
                        ]
                    ));
                    break;

                // When bot receive "set menu"
                case 'set menu':
                    $bot->deletePersistentMenu();
                    $myAccountItems[] = new MenuItem('postback', 'Pay Bill', 'PAYBILL_PAYLOAD');
                    $historyItems[]   = new MenuItem('postback', 'History Old', 'HISTORY_OLD_PAYLOAD');
                    $historyItems[]   = new MenuItem('postback', 'History New', 'HISTORY_NEW_PAYLOAD');
                    $myAccountItems[] = new MenuItem('nested', 'History', $historyItems);
                    $myAccountItems[] = new MenuItem('postback', 'Contact_Info', 'CONTACT_INFO_PAYLOAD');

                    $myAccount = new MenuItem('nested', 'My Account', $myAccountItems);
                    $promotions = new MenuItem('postback', 'Promotions', 'GET_PROMOTIONS_PAYLOAD');

                    $enMenu = new LocalizedMenu('default', false, [
                        $myAccount,
                        $promotions
                    ]);

                    $arMenu = new LocalizedMenu('ar_ar', false, [
                        $promotions
                    ]);

                    $localizedMenu[] = $enMenu;
                    $localizedMenu[] = $arMenu;
                    $bot->setPersistentMenu($localizedMenu);

                    // $bot->setPersistentMenu([
                    //     new LocalizedMenu('default', false, [
                    //         new MenuItem(MenuItem::TYPE_NESTED, 'My Account', [
                    //             new MenuItem(MenuItem::TYPE_NESTED, 'History', [
                    //                 new MenuItem(MenuItem::TYPE_POSTBACK, 'History Old', 'HISTORY_OLD_PAYLOAD'),
                    //                 new MenuItem(MenuItem::TYPE_POSTBACK, 'History New', 'HISTORY_NEW_PAYLOAD')
                    //             ]),
                    //             new MenuItem(MenuItem::TYPE_POSTBACK, 'Contact Info', 'CONTACT_INFO_PAYLOAD')
                    //         ])
                    //     ])
                    // ]);
                    $bot->send(new Message($message['sender']['id'], '👋😼😼👂🦁🐟🐟💮🥩🍕'));
                    break;

                // When bot receive "delete menu"
                case 'delete menu':
                    $bot->deletePersistentMenu();
                    break;

                // When bot receive "login"
                case 'login':
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_GENERIC,
                        [
                            'elements' => [
                                new AccountLink(
                                    'Welcome to Bank',
                                    'To be sure, everything is safe, you have to login to your administration.',
                                    'https://www.example.com/oauth/authorize',
                                    'https://www.facebook.com/images/fb_icon_325x325.png')
                            ]
                        ]
                    ));
                    break;

                // When bot receive "logout"
                case 'logout':
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_GENERIC,
                        [
                            'elements' => [
                                new AccountLink(
                                    'Welcome to Bank',
                                    'To be sure, everything is safe, you have to login to your administration.',
                                    '',
                                    'https://www.facebook.com/images/fb_icon_325x325.png',
                                    TRUE)
                            ]
                        ]
                    ));
                    break;

                // When bot receive "sender action on"
                case 'sender action on':
                    $bot->send(new SenderAction($message['sender']['id'], SenderAction::ACTION_TYPING_ON));
                    break;

                // When bot receive "sender action off"
                case 'sender action off':
                    $bot->send(new SenderAction($message['sender']['id'], SenderAction::ACTION_TYPING_OFF));
                    break;

                // When bot receive "set get started button"
                case 'set get started button':
                    $bot->setGetStartedButton('Сайн байна уу Практикал даатгалд хандсанд баярлалаа');
                    break;

                // When bot receive "delete get started button"
                case 'delete get started button':
                    $bot->deleteGetStartedButton();
                    break;

                // When bot receive "show greeting text"
                case 'show greeting text':
                    $response = $bot->getGreetingText();
                    $text = "";
                    if(isset($response['data'][0]['greeting']) AND is_array($response['data'][0]['greeting'])){
                        foreach ($response['data'][0]['greeting'] as $greeting)
                        {
                            $text .= $greeting['locale']. ": ".$greeting['text']."\n";
                        }
                    } else {
                        $text = "Greeting text not set!";
                    }
                    $bot->send(new Message($message['sender']['id'], $text));
                    break;

                // When bot receive "delete greeting text"
                case 'delete greeting text':
                    $bot->deleteGreetingText();
                    break;

                // When bot receive "set greeting text"
                case 'set greeting text':
                    $bot->setGreetingText([
                        [
                            "locale" => "default",
                            "text" => "Hello {{user_full_name}}"
                        ]
                    ]);
                    break;


                // Other message received
                default:
                    if (!empty($command)) // otherwise "empty message" wont be understood either
                    {
                        //$bot->send(new Message($message['sender']['id'], 'Sorry. I don’t understand you.'));

                        $bot->send(new QuickReply($message['sender']['id'], 'Уучилаарай таны бичсэнг ойлгосонгүй доорх цэснээс сонгоно уу.', 
                            [
                                new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Үндсэн цэс', 'Үндсэн цэс'),
                                new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Бусад цэс', 'Бусад цэс'),
                            ]
                    ));
                    break;
                    }
                    
            }
        }
    }
}
