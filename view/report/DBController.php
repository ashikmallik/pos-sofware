<?php
class DBController {
    private $host = 'localhost'; // Database host
    private $db = 'bsd1'; // Database name
    private $user = 'root'; // Database username
    private $pass; // Database password
    private $charset = 'utf8'; // Use utf8 if utf8mb4 causes issues
    private $pdo; // PDO instance

  public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a query and return the result as an associative array.
     * 
     * @param string $query The SQL query.
     * @param array $params Optional parameters for prepared statements.
     * @return array The result set.
     */
    public function runQuery($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Query execution failed: " . $e->getMessage());
        }
    }

    /**
     * Fetch sell and purchase report data, including the Product Name column.
     * 
     * @param string $startDate The start date for filtering.
     * @param string $endDate The end date for filtering.
     * @return array The result set with sell and purchase report data.
     */
    public function getSellPurchaseReport($startDate, $endDate) {
        $query = "
            SELECT 
                p.product_name,
                AVG(p.total_price / p.total_qty) AS avg_purchase_price,
                AVG(s.total_price / s.total_qty) AS avg_sell_price,
                SUM(p.total_qty) AS total_purchase_qty,
                SUM(s.total_qty) AS total_sell_qty
            FROM 
                vw_purchase_item p
            LEFT JOIN 
                vw_sell_item s ON p.bill_id = s.sell_id
            WHERE 
                (p.entry_date BETWEEN ? AND ? OR s.entry_date BETWEEN ? AND ?)
            GROUP BY 
                p.product_name";

        $params = [$startDate, $endDate, $startDate, $endDate];
        return $this->runQuery($query, $params);
    }

    /**
     * Close the database connection.
     */
    public function closeConnection() {
        $this->pdo = null;
    }
}
?>
