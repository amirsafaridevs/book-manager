<?php
namespace BookManager\Helpers;

use Rabbit\Utils\Arr;
use Rabbit\Utils\Sanitizer;

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
            'singular' => Arr::get($args, 'singular', 'item'),
            'plural' => Arr::get($args, 'plural', 'items'),
            'ajax' => Arr::get($args, 'ajax', false),
        ]);

        $this->perPage = (int) Arr::get($args, 'per_page', $this->perPage);
        $this->primaryKey = Arr::get($args, 'primary_key', $this->primaryKey);
        $this->showCheckbox = (bool) Arr::get($args, 'show_checkbox', $this->showCheckbox);
    }

    /**
     * Set table data
     * 
     * @param array|object $data Data array or Eloquent Collection
     * @return $this
     */
    public function setData($data)
    {
        // Convert Eloquent Collection to array
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        } elseif (is_object($data)) {
            // Convert other objects to array
            $dataArray = [];
            foreach ($data as $item) {
                if (is_object($item) && method_exists($item, 'toArray')) {
                    $dataArray[] = $item->toArray();
                } elseif (is_object($item)) {
                    $dataArray[] = (array) $item;
                } else {
                    $dataArray[] = $item;
                }
            }
            $data = $dataArray;
        }
        
        // Ensure it's an array
        if (!is_array($data)) {
            $data = [];
        }
        
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
                $this->columns[$key] = Arr::get($column, 'label', ucfirst($key));
                
                if (Arr::get($column, 'sortable') === true) {
                    $this->sortableColumns[$key] = [$key, false];
                }
                
                // Store callback for custom rendering
                $callback = Arr::get($column, 'callback');
                if (is_callable($callback)) {
                    $this->columnCallbacks[$key] = $callback;
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
        // Ensure columns are set
        if (empty($this->columns)) {
            return [];
        }
        
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
     * Get nested value from an item (supports dot notation)
     * 
     * @param mixed $item The item (array or object)
     * @param string $key The key (supports dot notation like 'book.post_title')
     * @return mixed
     */
    protected function getNestedValue($item, $key)
    {
        // Use Arr::dataGet which supports both arrays and objects with dot notation
        return Arr::dataGet($item, $key);
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
        
        // Get value (supports nested properties like 'book.post_title')
        $value = $this->getNestedValue($item, $column_name);
        
        if ($value !== null) {
            return esc_html($value);
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
        $value = Arr::get($item, $this->primaryKey, '');
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
        // Get filtered data first
        $data = $this->filter_data();
        
        // Get sort parameters
        $orderby = Sanitizer::clean(Arr::get($_GET, 'orderby', ''));
        $order = Sanitizer::clean(Arr::get($_GET, 'order', 'asc'));

        // Sort data
        if ($orderby && isset($this->sortableColumns[$orderby])) {
            usort($data, function($a, $b) use ($orderby, $order) {
                // Support nested values
                $valA = $this->getNestedValue($a, $orderby) ?? '';
                $valB = $this->getNestedValue($b, $orderby) ?? '';
                
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
        
        // Set column headers manually (WP_List_Table format)
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $primary = $this->primaryKey;
        
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);
    }

    /**
     * Filter and search data
     * 
     * @return array
     */
    protected function filter_data()
    {
        $data = $this->tableData;
        $search = Sanitizer::clean(Arr::get($_GET, 's', ''));

        if (empty($search)) {
            return $data;
        }

        // Search in all columns (including nested ones)
        $filtered = array_filter($data, function($item) use ($search) {
            // Search in defined columns
            foreach ($this->columns as $key => $label) {
                $value = $this->getNestedValue($item, $key);
                if ($value !== null && stripos((string) $value, $search) !== false) {
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
        // Only prepare if not already prepared
        if (empty($this->items)) {
            $this->prepare_items();
        }
        
        // Call parent display
        parent::display();
    }

    /**
     * Store search configuration
     */
    protected $searchLabel = '';
    protected $showSearchBox = true;
    
    /**
     * Display search box in extra tablenav area (aligned with pagination)
     * 
     * @param string $which Search position (top or bottom)
     */
    protected function extra_tablenav($which)
    {
        if ($which === 'top' && $this->showSearchBox && !empty($this->searchLabel)) {
            ?>
            <div class="alignleft actions">
                <?php $this->search_box($this->searchLabel, 'search'); ?>
            </div>
            <?php
        }
    }

    /**
     * Static method for quick table creation
     * 
     * @param array|object $data Table data or Eloquent Collection
     * @param array $columns Table columns
     * @param array $args Additional arguments
     * @return Table
     */
    public static function create($data, array $columns, array $args = [])
    {
        $table = new self($args);
        $table->setData($data);
        $table->setColumns($columns);
        return $table;
    }
    
    /**
     * Quick display method - creates and displays table in one call
     * 
     * Features enabled by default:
     * - Search box (always displayed)
     * - Pagination (per_page defaults to 20)
     * - Sortable columns (all columns sortable by default)
     * 
     * @param array|object $data Table data or Eloquent Collection
     * @param array $columns Table columns in format ['column_key' => 'Column Label']
     * @param array $args Additional arguments:
     *                    - 'singular': Singular name (default: 'item')
     *                    - 'plural': Plural name (default: 'items')
     *                    - 'per_page': Items per page (default: 20)
     *                    - 'primary_key': Primary key column (default: 'id')
     *                    - 'search_label': Search box label (default: 'Search')
     *                    - 'show_search': Show search box (default: true)
     * @return void
     */
    public static function show($data, array $columns, array $args = [])
    {
        // Set defaults
        $defaults = [
            'per_page' => 20,
            'primary_key' => 'id',
            'search_label' => __('Search', 'book-manager'),
            'show_search' => true,
        ];
        
        $args = array_merge($defaults, $args);
        
        $table = self::create($data, $columns, $args);
        
        // Set search configuration
        $table->searchLabel = $args['search_label'];
        $table->showSearchBox = $args['show_search'];
        
        // Prepare items before displaying
        $table->prepare_items();
        
        // Display in form wrapper (required for search and pagination)
        ?>
        <form method="get">
            <input type="hidden" name="page" value="<?php echo esc_attr(Sanitizer::clean(Arr::get($_GET, 'page', ''))); ?>" />
            <?php 
            // Display table (search box will be displayed automatically in extra_tablenav)
            $table->display();
            ?>
        </form>
        <?php
    }
}
