# Generates Repository

``php artisan make:repository <Name>``

Optional Params

`c` -> to generate the Controller

`m` -> to generate the Model

Example:

``php artisan make:respository book m c``

-- Creates following files under directory structure

    -- app
        --Http
            -- Controllers
                -- BookController.php
    -- Repository
        -- Book
            -- BookContract.php
            -- BookRepository.php
    -- Book.php

Generated Controller will be as follows

```
namespace App\Http\Controllers;

<?php 

class BookController extends Controller {
    
    /**
    * @var BookContract
    */
    private $bookContract;

    /**
    * BookController constructor
    * BookContract $bookContract
    */
    public function __construct(BookContract $bookContract) 
    {
        $this->bookContract = $bookContract;
    }

}

```

Generated Repository be like

- if `m` flag is not send you won't get the constructor in generated repository

```

<?php

namespace App\Repository\Book;

use App\Book;

class BookRepository implements BookContract
{

    /**
     * @var Book
     */
    private $book;

    /**
     * BookRepository constructor.
     * @param  Book  $book
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    // your code goes here
}

```

Generated interface will be like

```

<?php

namespace App\Repository\Book;

interface BookContract
{
    // your code goes here 
}

```

Generated Model will be like

```

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // your code goes here
}

```


###### Developed by: [Mahesh Rao](https://brainlabsweb.com)
    
