<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\UsersAssingedRole;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class UsersAssignedRoleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['user'])
            ->editColumn('user', function (User $user) {
                return view('pages.apps.user-management.roles.columns._user', compact('user'));
            })
            ->editColumn('role_assigned_at', function (User $user) {
                return $user->role_assigned_at 
                ? Carbon::parse($user->role_assigned_at)->format('d M Y') 
                : 'Not Assigned';
            })
            ->addColumn('action', function (User $user) {
                return view('pages.apps.user-management.roles.columns._actions', compact('user'));
            })
            ->setRowId('id')
            ->rawColumns(['action', 'role_assigned_at']);

    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->whereHas('roles', function (Builder $query) {
                $query->where('role_id', $this->role->getKey());
            })
            ->leftJoin('model_has_roles', function ($join) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', User::class)
                    ->where('model_has_roles.role_id', '=', $this->role->getKey());
            })
            ->select('users.*', 'model_has_roles.created_at as role_assigned_at');
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('usersassingedrole-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/user-management/roles/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('user')->addClass('d-flex align-items-center')->title('Admin')->name('name'),
            // Column::make('name'),
            Column::computed('role_assigned_at')->title('Assign Date')->addClass('text-nowrap'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UsersAssingedRole_' . date('YmdHis');
    }
}