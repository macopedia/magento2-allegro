# Magento 2 - Allegro Integration Module
Magento 2 Allegro Integration module

## Partners
Our partners helps to develop this project.
* [Macopedia.com](https://macopedia.com)
* [Oceanic](https://www.oceanic.com.pl)
* [Trefl](https://sklep.trefl.com/)

If you like to help our project - please let us know at [sales@macopedia.com](mailto:sales@macopedia.com)

## Current Backlog
* [Multi variants](https://github.com/macopedia/magento2-allegro/issues/1)
* [Multi sellers account handling](https://github.com/macopedia/magento2-allegro/issues/2)
* [Price policy & price automation](https://github.com/macopedia/magento2-allegro/issues/3)
* [Mass offers issuing](https://github.com/macopedia/magento2-allegro/issues/4)
* [Manual order handling - choosing correct simple product from configurable](https://github.com/macopedia/magento2-allegro/issues/5)
* [Mapping Allegro categories to Magento](https://github.com/macopedia/magento2-allegro/issues/6)
* [Mapping Allegro attributes to Magento](https://github.com/macopedia/magento2-allegro/issues/7)


## License 
Magento 2 - Allegro Integration Module source code is completely free and released under the [MIT License](https://github.com/macopedia/magento2-allegro/blob/master/LICENSE).

## Powiązanie istniejących ofert Allegro z produktami w sklepie Magento
Przed połączeniem z kontem Allegro należy powiązać już istniejące oferty na Allegro z odpowiadającymi im produktami w sklepie Magento, aby to zrobić należy wykonać następujące kroki:

1. Wejść  w zakładkę "Moje oferty" na koncie Allegro
2. Skopiować ID oferty znajdujące się pod jej nazwą
![offer_id2](README/allegroOfferId2.png)
3. Wejść na stronę edycji produktu w Magento, który odpowiada ofercie Allegro
4. Wkleić skopiowane wcześniej ID oferty do pola Allegro→ Numer oferty Allegro i zapisać produkt
![offer_id1](README/allegroOfferId1.png)

## Połączenie z kontem Allegro
Aby połączyć sklep Magento z aplikacją Allegro należy wykonać następujące kroki:
1. Zalogować się na koncie Allegro i przejść na adres https://apps.developer.allegro.pl lub https://apps.developer.allegro.pl.allegrosandbox.pl dla konta sandoboxowego, aby zarejestrować nową aplikację.
2. Wprowadzić nazwę aplikacji i adres URI do przekierowania - powinien on być w formacie {backend_url}/index.php/admin/allegro/system/authenticate/ - np. 'http://example.com/index.php/admin/allegro/system/authenticate/'.
![application_registration](README/applicationRegistration.png)
3. Zalogować się w panelu admina w Magento i przejść do sekcji Sklepy -> Konfiguracja -> Allegro -> Konfiguracja
![connection_configuration](README/allegroConnectionConfiguration.png)
4. Wprowadzić w konfiguracji Magento wartości Client ID i Client Secret wygenerowane dla aplikacji Allegro i zmienić typ konta jeśli działamy na koncie sandboxowym.
![api_keys](README/apiKeys.png)
5. Kliknąć na przycisk "Połącz z kontem Allegro" aby otrzymać token z aplikacji Allegro. Jeśli połączenie zostanie nawiązane pomyślnie, token będzie zapamiętany w aplikacji Magento, co umożliwi dalszą integrację z kontem Allegro.


## Synchronizacja stanów magazynowych
Jednym z wielu zadań wtyczki jest dopilnowanie, aby liczba danego produktu na sklepie Magento,
powiązanego z odpowiednią ofertą w Allegro była stale taka sama zarówno na sklepie jak i w
Allegro. Zadanie to spełnia realizując poniższe czynności:
1. Monitoruje każdorazowe wystąpienie zmiany w liczbie produktu (zakup lub ręczna zmiana w
panelu admina Magento) i pobiera ID produktu, w którym doszło do zmian.
2. Następnie przy pomocy RabbitMQ lub MySQL MQ przekazuje pobrane wcześniej ID produktu do kolejki, aby
synchronizacja przebiegała stopniowo, przechodząc po kolei po każdej wystąpionej zmianie
stanu magazynowego (dzięki temu stany magazynowe aktualizowane są na bieżąco zgodnie z
kolejnością zmian i odciąża system, ponieważ zmiany nie są wykonywane jednocześnie).
3. Gdy w kolejce zaczynają pojawiać się nowe ID produktów, Consumer - klasa odpowiadająca
za modyfikowanie stanów magazynowych ofert na Allegro, odbiera pierwszy ID z kolejki, a
następnie przy jego pomocy wyciąga informacje o aktualnym stanie magazynowym danego
produktu i przesyła go do Allegro w celu aktualizacji.

![stock_inventory_synchronization](README/stockInventorySynchronizationDiagram.png)

## Integracja zamówień
Po nawiązaniu połączenia sklepu z aplikacją Allegro możemy włączyć w konfiguracji import zamówień.
![orders_configuration](README/orderImportConfiguration.png)

Po włączeniu tej opcji API Allegro będzie odpytywane co minutę o zdarzenia dotyczące zamówień. W ramach tego zapytania zamówienia będą importowane w sklepie Magento - dla nowych zamówień dodanych w Allegro będą tworzone zamówienia w sklepie Magento, a dla już istniejących będzie przeprowadzana ich aktualizacja.

W konfiguracji sklepu możemy również ustawić widok sklepu do którego zamówienia z Allegro będą importowane.

W ramach importu zamówień z Allegro w sklepie Magento zapisywane są informacje o cenie i ilości zamówionego produktu, dane zamawiającego, dane o płatności i wysyłce oraz wiadomość do sprzedającego, która trafia do zakładki "Historia komentarzy" na stronie zamówienia.

Moduł obsługuje standardową logikę dla składania zamówień w Magento. Dostosowanie importowanych produktów można w projekcie przeprowadzić poprzez utworzenie obserwera dla eventu z nazwą "allegro_order_import_before_quote_save". Obserwer ten ma przekazane w parametrze wszystkie informacje udostępniane przez API Allegro dla zapytania o szczegóły zamówienia (https://developer.allegro.pl/en/orders/#04).

## Mapowanie metod dostawy i płatności
W konfiguracji wtyczki możemy definiować mapowanie metod płatności dla zamówień przychodzących z Allegro do sklepu Magento.
![method_mapping](README/deliveryAndPaymentMapping.png)

Dla mapowania metod dostawy mamy do dyspozycji dynamiczną listę, do której możemy dodawać kolejne pozycje, w których wybieramy w liście po lewej stronę jedną z metod dostawy dostępnych w Allegro, a w liście po prawej stronie nazwę metody dostawy dostępnej i aktywnej w konfiguracji sklepu Magento. Poniżej dynamicznej listy możemy wybrać domyślną metodę dostawy, która będzie przypisana do zamówienia w momencie gdy z Allegro otrzymamy metodę dla której nie zdefiniowaliśmy mapowania.

Dla mapowania metod płatności mamy do dyspozycji dwie listy rozwijane, w których możemy wybrać po jednej z dostępnych i aktywnych w konfiguracji sklepu Magento metod płatności - dla zamówień przychodzących z Allegro z płatnością online i dla zamówień z płatnością przy pobraniu. 

## Wysyłanie numerów przesyłek
Aby klient mógł śledzić przesyłkę z jego zamówieniem należy wprowadzić w Allegro jej numer oraz informacje o przewoźniku. Dzięki wtyczce można, to zrobić z poziomu Magento:
1. Należy wejść na stronę zamówienia, które zostało wcześniej zaimportowane z Allegro i otworzyć zakładkę 'Dostawa'.
![tracking_information1](README/sendTrackingInformation1.png)
2. Następnie kliknąć 'Dodaj numer przesyłki', wybrać nazwę przewoźnika i wprowadzić numer do śledzenia przesyłki.
3. Można również wybrać, które produkty znajdują się w przesyłce poprzez ustawienie ilości produktu do wysłania:
    - 0 - produkt nie znajduje się w przesyłce.
    - liczba większa od 0 - produkt znajduje się w przesyłce w podanej ilości.
4. Można dodać wiele numerów przesyłek w zależności od ilości produktów w zamówieniu.    
![tracking_information2](README/sendTrackingInformation2.png)

## Konfiguracja MYSQL MQ

dodać kolejkę w tabeli `queue` w bazie danych

`` INSERT INTO queue (name) VALUES ('allegro.api'); ``


konfiguracja w pliku config.php
````
<?php
return [
    'modules' => [
    // ...
    'Magento_Amqp' => 0, // important disable rabbitmq
    'Magento_MysqlMq' => 1,
    // ...
    ]
````

konfiguracja kolejki w pliku env.php

````
    'queue' => [
        'topics' => [
            'allegro.change.stock.db' => [
                'schema' => [
                    'schema_value' => 'Macopedia\Allegro\Api\Consumer\MessageInterface'
                ],
                'response_schema' => [
                    'schema_value' => 'Macopedia\Allegro\Api\Consumer\MessageInterface'
                ],
                'publisher' => 'allegro.change.stock.db',
            ],
        ],
        'publishers' => [
            'allegro.change.stock.db' => [
                'name' => 'allegro.change.stock.db',
            ]
        ],
        'consumers' => [
            'allegro.change.stock.db' => [
                'queue' => 'allegro.api', // `name` from db table `queue`
                'name' => 'allegro.change.stock.db',
                'handlers' => [
                    [
                        'type' => 'Macopedia\Allegro\Model\Consumer',
                        'method' => 'processMessage'
                    ]
                ],
                'consumerInstance' => 'Magento\Framework\MessageQueue\Consumer',
                'instance_type' => 'Magento\Framework\MessageQueue\Consumer',
                'connection' => 'db',
                'maxMessages' => 2000,
                'max_messages' => 2000
            ]
        ],
        'exchange_topic_to_queues_map' => [
            'allegro.change.stock.db--allegro.change.stock.db' => [
                'allegro.api' // `name` from db table `queue`
            ]
        ]
    ],
````
konfiguracja consumera w pliku env.php

````
    'cron_consumers_runner' => [
        'cron_run' => true,
        'max_messages' => 20000,
        'consumers' => [
            'AllegroApiQueueDb'
        ]
    ]
````

## Konfiguracja RABBITMQ

konfiguracja w pliku config.php
````
<?php
return [
    'modules' => [
    // ...
    'Magento_Amqp' => 1, // important enable rabbitmq
    // ...
    ]
````

konfiguracja kolejki w pliku env.php

````
    'queue' => [
        'amqp'   => [
            'host'     => 'amqp',
            'port'     => '5672',
            'user'     => 'guest',
            'password' => 'guest',
        ],
    ],
````
konfiguracja consumera w pliku env.php

````
    'cron_consumers_runner' => [
        'cron_run' => true,
        'max_messages' => 20000,
        'consumers' => [
            'AllegroApiQueue'
        ]
    ]
````
