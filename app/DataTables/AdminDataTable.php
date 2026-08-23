<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class AdminDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['user', 'last_login_at'])
            ->editColumn('admin', function (User $user) {
                return view('pages.apps.user-management.admin.columns._user', compact('user'));
            })
            ->editColumn('role', function (User $user) {
                $role = $user->roles->first()?->name ?? 'No Role';
                $badgeClass = app(\App\Actions\GetThemeType::class)->handle('badge-light-?', $role);
                
                return "<span class='badge {$badgeClass}'>{$role}</span>";                
            })            
            ->editColumn('id', function (User $user) {
                return '<span class="badge badge-light-primary">' . $user->id . '</span>';
            })
            ->editColumn('last_login_at', function (User $user) {
                return sprintf('<div class="badge badge-light fw-bold">%s</div>', $user->last_login_at ? $user->last_login_at->diffForHumans() : 'No login yet');
            })
            ->editColumn('created_at', function (User $user) {
                return $user->created_at->format('d M Y');
            })
            ->addColumn('status', function (User $user) {
                return view('pages.apps.user-management.admin.columns._active_status', compact('user'));
            })
            ->addColumn('action', function (User $user) {
                return view('pages.apps.user-management.admin.columns._actions', compact('user'));
            })
            ->setRowId('id')
            ->rawColumns(['role', 'last_login_at', 'status', 'id']);
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {   
        return $model->newQuery()->where('isAdmin', 1);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(
                "rt" . "<'row align-items-center'<'col-sm-12 col-md-6 d-flex gap-3'l i><'col-sm-12 col-md-6 d-flex justify-content-end'p>>"
            )
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages//apps/user-management/admin/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            //Column::make('email'),
            //  Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('admin')->addClass('d-flex align-items-center')->name('name'),
            Column::make('role')->searchable(false)->addClass('align-items-center'),
            Column::make('last_login_at')->title('Last Login'),
            Column::make('id')->title('User ID')->addClass('text-center'),
            Column::make('created_at')->title('Joined Date')->addClass('text-nowrap'),
            Column::computed('status')
                ->title('Status')
                ->addClass('text-center text-nowrap no-export') 
                ->exportable(false)
                ->printable(false),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
