<?php

namespace App\DataTables;

use App\Models\BotCategoryGroup;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class BotCategoryGroupDataTable extends DataTable
{

    //to help with data testing and form settings
    public $link;
    public $htmlTag;
    public $title;
    public $resourceFolder;

    /**
     * receive the value from the controller to parameterise the display of the table
     * @param $array
     */
    public function setDrawParams($array)
    {

        $this->link = $array['link'];
        $this->htmlTag = $array['htmlTag'];
        $this->title = $array['title'];
        $this->resourceFolder = $array['resourceFolder'];
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', function ($row) {

            if (isset($row['slug'])) {
                $id = $row['slug'];
            } else {
                $id = $row['id'];
            }

            return view(
                $this->resourceFolder.'.datatables_actions',
                ['id'=>$id, 'title'=>$this->title, 'htmlTag'=>$this->htmlTag,
                'link'=>$this->link,
                'bot'=>$row['bot']]
            );
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\GitDetail $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BotCategoryGroup $model)
    {
        return $model->dataTableQuery();
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
             ->addAction(['width' => '120px', 'printable' => false,'searchable'=>false, 'exportable'=>false])
            ->parameters([
                'drawCallback' => 'function(settings, json) {
                    addRowFeatures(settings, json, "'.$this->link.'","edit")
                }',
                'initComplete' => 'function(settings, json) {
                    
                    var maxColumn = 4
                    var dateFields = [maxColumn-1]
                    var exactSearchFields = [0]
                    var noSearchFields = [maxColumn]
                
                    runAutoSearch(settings, json)
                    addFooterSearch(settings, json, dateFields ,exactSearchFields,noSearchFields)
                }',
                'dom'       => 'Bfrtip',
                'pageLength' => 25,
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner create-item',],
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner export-items',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner print-items',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner reset-table',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner reload-table',],
                ],
            ]);
    }



    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'bot'=> ['name'=>'bots.slug','data'=>'bot','title'=>'BotId'],
            'category_group'=> ['title'=>'Category Group'],
            'email'=> ['name'=>'users.email','data'=>'email','title'=>'Owner', 'exportable'=>false],
            'updated_at'=> ['name'=>'updated_at','data'=>'updated_at', 'title'=>'Updated',
                'defaultContent'=>'', 'exportable'=>false, 'render' =>
                function () {
                    return 'function(data, type, full, meta)
                { 
                    return moment(data).format("lll"); // "02 Nov 16 12:00AM"        
                 }
                 ';
                }],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'bot_category_groups_datatable_' . time();
    }
}
