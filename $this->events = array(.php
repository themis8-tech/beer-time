$this->events = array(
         array(
            'id' => 1,
            'name' => "Ypres discovery",
            'description' => "Découverte des bières d'une micro-brasserie de Ypres",
            'startAt' => new DateTime('2021-02-10 16:00:00'),
            'endAt' => new DateTime('2021-02-10 20:00:00'),
            'picture' => '../img/brasserie.jpg',
            'price' => null,
            'capacity' => 50,
            'place' => "Le Cheval Blanc"

         ),
         array(
            'id' => 2,
            'name' => "Stout contest",
            'description' => "Concours de la meilleure stout",
            'startAt' => new DateTime("2021-01-14 16:00:00"),
            'endAt' => new DateTime("2021-01-18 20:00:00"),
            'price' => 10,
            'capacity' => 30,
            'picture' => '../img/stout.jpg',
            'place' => "Le Triporteur"
         ),
         array(
            'id' => 3,
            'name' => "Celestin stylee",
            'description' => "Dégustation de bières de la brasserie lilloise Celestin",
            'startAt' => new DateTime("2021-01-18 16:00:00"),
            'endAt' => new DateTime("2021-04-25 20:00:00"),
            'picture' => '../img/amber.jpg',
            'price' => 12,
            'capacity' => 60,
            'place' => "Le Lovibon"
         ),
