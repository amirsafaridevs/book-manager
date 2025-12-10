<?php
namespace BookManager\Helpers;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Simple WordPress Table Helper
 * 
 * This class is used to create WordPress tables easily and automatically.
 * It automatically enables sorting and searching capabilities for all columns.
 * 
 * @package BookManager\Helpers
 */
class Table extends \WP_List_Table
{
    /**
     * Table data
     * 
     * @var array
     */
    protected $tableData = [];

    /**
     * Column definitions
     * 
     * @var array
     */
    protected $columns = [];

    /**
     * Sortable columns
     * 
     * @var array
     */
    protected $sortableColumns = [];

    /**
     * Number of items per page
     * 
     * @var int
     */
    protected $perPage = 20;

    /**
     * Primary key for identifying rows
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Show checkbox column
     * 
     * @var bool
     */
    protected $showCheckbox = false;

    /**
     * Custom callbacks for rendering columns
     * 
     * @var array
     */
    protected $columnCallbacks = [];

    /**
     * Constructor
     * 
     * @param array $args Initial arguments
     */
    public function __construct($args = [])
    {
        parent::__construct([
            'singular' => isset($args['singular']) ? $args['singular'] : 'item',
            'plural' => isset($args['plural']) ? $args['plural'] : 'items',
            'ajax' => isset($args['ajax']) ? $args['ajax'] : false,
        ]);

        if (isset($args['per_page'])) {
            $this->perPage = (int) $args['per_page'];
        }

        if (isset($args['primary_key'])) {
            $this->primaryKey = $args['primary_key'];
        }

        if (isset($args['show_checkbox'])) {
            $this->showCheckbox = (bool) $args['show_checkbox'];
        }
    }

    /**
     * Set table data
     * 
     * @param array $data Data array
     * @return $this
     */
    public function setData(array $data)
    {
        $this->tableData = $data;
        return $this;
    }

    /**
     * Set table columns
     * 
     * @param array $columns Column array in format ['key' => 'Label'] or ['key' => ['label' => 'Label', 'sortable' => true, 'callback' => function]]
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = [];
        $this->sortableColumns = [];
        $this->columnCallbacks = [];

        foreach ($columns as $key => $column) {
            if (is_string($column)) {
                // Simple format: ['key' => 'Label']
                $this->columns[$key] = $column;
                // By default, all columns are sortable
                $this->sortableColumns[$key] = [$key, false];
            } elseif (is_array($column)) {
                // Advanced format: ['key' => ['label' => 'Label', 'sortable' => true, 'callback' => function]]
                $this->columns[$key] = isset($column['label']) ? $column['label'] : ucfirst($key);
                
                if (isset($column['sortable']) && $column['sortable'] === true) {
                    $this->sortableColumns[$key] = [$key, false];
                }
                
                // Store callback for custom rendering
                if (isset($column['callback']) && is_callable($column['callback'])) {
                    $this->columnCallbacks[$key] = $column['callback'];
                }
            }
        }

        return $this;
    }

    /**
     * Get column names
     * 
     * @return array
     */
    public function get_columns()
    {
        $columns = $this->columns;
        
        // Add checkbox column if enabled
        if ($this->showCheckbox) {
            $columns = array_merge(['cb' => '<input type="checkbox" />'], $columns);
        }
        
        return $columns;
    }

    /**
     * Get sortable columns
     * 
     * @return array
     */
    public function get_sortable_columns()
    {
        return $this->sortableColumns;
    }

    /**
     * Get value for column
     * 
     * @param array $item Current item
     * @param string $column_name Column name
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        // Use custom callback if available
        if (isset($this->columnCallbacks[$column_name])) {
            return call_user_func($this->columnCallbacks[$column_name], $item, $column_name);
        }
        
        // Otherwise return default value
        if (isset($item[$column_name])) {
            return esc_html($item[$column_name]);
        }
        
        return '—';
    }

    /**
     * Checkbox column for bulk actions
     * 
     * @param array $item
     * @return string
     */
    public function column_cb($item)
    {
        $primaryKey = $this->primaryKey;
        $value = isset($item[$primaryKey]) ? $item[$primaryKey] : '';
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />',
            esc_attr($value)
        );
    }

    /**
     * Prepare data for display
     * Includes filtering, searching, sorting and pagination
     */
    public function prepare_items()
    {
        // Get filtered data
        $data = $this->filter_data();
        
        // Get sort parameters
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
        $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'asc';

        // Sort data
        if ($orderby && isset($this->sortableColumns[$orderby])) {
            usort($data, function($a, $b) use ($orderby, $order) {
                $valA = isset($a[$orderby]) ? $a[$orderby] : '';
                $valB = isset($b[$orderby]) ? $b[$orderby] : '';
                
                // Convert to string for comparison
                $valA = is_numeric($valA) ? (float) $valA : strtolower((string) $valA);
                $valB = is_numeric($valB) ? (float) $valB : strtolower((string) $valB);
                
                if ($valA == $valB) {
                    return 0;
                }
                
                $result = ($valA < $valB) ? -1 : 1;
                return ($order === 'desc') ? -$result : $result;
            });
        }

        // Pagination
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args([
            'total_items' => $totalItems,
            'per_page' => $this->perPage,
            'total_pages' => ceil($totalItems / $this->perPage)
        ]);

        $data = array_slice($data, (($currentPage - 1) * $this->perPage), $this->perPage);

        $this->items = $data;
        
        // Set columns
        $this->_column_headers = $this->get_column_info();
    }

    /**
     * Filter and search data
     * 
     * @return array
     */
    protected function filter_data()
    {
        $data = $this->tableData;
        $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

        if (empty($search)) {
            return $data;
        }

        // Search in all columns
        $filtered = array_filter($data, function($item) use ($search) {
            foreach ($item as $value) {
                if (stripos((string) $value, $search) !== false) {
                    return true;
                }
            }
            return false;
        });

        return array_values($filtered);
    }

    /**
     * Display table
     * 
     * @return void
     */
    public function display()
    {
        $this->prepare_items();
        parent::display();
    }

    /**
     * Display search
     * 
     * @param string $which Search position (top or bottom)
     */
    protected function extra_tablenav($which)
    {
        if ($which === 'top') {
            // Search is automatically displayed in WP_List_Table
        }
    }

    /**
     * Static method for quick table creation
     * 
     * @param array $data Table data
     * @param array $columns Table columns
     * @param array $args Additional arguments
     * @return Table
     */
    public static function create(array $data, array $columns, array $args = [])
    {
        $table = new self($args);
        $table->setData($data);
        $table->setColumns($columns);
        return $table;
    }
}
