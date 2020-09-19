<?php
class Product {

    // подключение к базе данных и таблице 'products' 
    private $conn;
    private $table_name = "product";

    // свойства объекта 
    public $id;
    public $src;
    public $product;
    public $code;
    public $brendmarket;
    public $price;
    public $date;
    public $url;    
    // конструктор для соединения с базой данных 
    public function __construct($db){
        $this->conn = $db;
    }

    // здесь будет метод read()
    function read(){

    // выбираем все записи 
    $query = "SELECT * FROM
                " . $this->table_name . "
            ORDER BY
                date DESC";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // выполняем запрос 
    $stmt->execute();

    return $stmt;
}

function create(){

    // запрос для вставки (создания) записей 
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                src=:src, product=:product, code=:code, brendmarket=:brendmarket, url=:url, price=:price, date=:date";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка 
    $this->product=htmlspecialchars(strip_tags($this->product));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->src=htmlspecialchars(strip_tags($this->src));
    $this->brendmarket=htmlspecialchars(strip_tags($this->brendmarket));
    $this->date=htmlspecialchars(strip_tags($this->date));
    $this->code=htmlspecialchars(strip_tags($this->code));
     $this->code=htmlspecialchars(strip_tags($this->url));
    // привязка значений 
    $stmt->bindParam(":product", $this->product);
    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":brendmarket", $this->brendmarket);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":date", $this->date);
    $stmt->bindParam(":src", $this->src);
    $stmt->bindParam(":url", $this->url);
    // выполняем запрос 
    if ($stmt->execute()) {
        return true;
    }

    return false;
}
// используется при заполнении формы обновления товара 
function readOne() {

    // запрос для чтения одной записи (товара) 
    $query = "SELECT
               *
            FROM
                " . $this->table_name . "
            WHERE
                id = ?
            LIMIT
                0,1";

    // подготовка запроса 
    $stmt = $this->conn->prepare( $query );

    // привязываем id товара, который будет обновлен 
    $stmt->bindParam(1, $this->id);

    // выполняем запрос 
    $stmt->execute();

    // получаем извлеченную строку 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // установим значения свойств объекта 
    $this->src = $row['src'];
    $this->product = $row['product'];
    $this->code = $row['code'];
    $this->brendmarket = $row['brendmarket'];
    $this->url = $row['url'];
    $this->price = $row['price']; 
    $this->date = $row['date'];
    
}

function update(){

    // запрос для обновления записи (товара) 
    $query = "UPDATE
                " . $this->table_name . "
            SET
            src=:src,
                product = :product,
                price = :price,
                code = :code,
                brendmarket = :brendmarket,
                    url = :url,
                date=:date
            WHERE
                id = :id";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка 
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->url=htmlspecialchars(strip_tags($this->url));
    // привязываем значения 
    $stmt->bindParam(':src', $this->src);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':product', $this->product);
    $stmt->bindParam(':code', $this->code);
    $stmt->bindParam(':id', $this->id);
     $stmt->bindParam(':date', $this->date);
     $stmt->bindParam(':url', $this->url);
      $stmt->bindParam(':brendmarket', $this->brendmarket);
    // выполняем запрос 
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

function delete(){

    // запрос для удаления записи (товара) 
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка 
    $this->id=htmlspecialchars(strip_tags($this->id));

    // привязываем id записи для удаления 
    $stmt->bindParam(1, $this->id);

    // выполняем запрос 
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

function search($keywords){

    // выборка по всем записям 
    $query = "SELECT
              *
            FROM
                " . $this->table_name . "
            WHERE
                product LIKE ? OR date LIKE ? OR id LIKE ? OR url LIKE ?
            ORDER BY
                date DESC";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка 
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // привязка 
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);
    $stmt->bindParam(4, $keywords);
    // выполняем запрос 
    $stmt->execute();

    return $stmt;
}
}
?>