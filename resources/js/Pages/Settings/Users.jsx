import React, { useCallback, useMemo, useRef, useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Table from "@/Components/Table/Table";
import {
  AvatarCell,
  StatusPill,
  DateCell,
  ActionCell,
} from "@/Components/Table/TableCell";
import { Link, usePage } from "@inertiajs/inertia-react";
import { TransformData } from "@/Helper/Transform";
import { Inertia } from "@inertiajs/inertia";
import YesNoDialog from "@/Components/Dialog/YesNoDialog";
import Dropdown from "@/Components/Dropdown";

function customAction(value, column, row) {
  return (
    <Dropdown.Content>
      <Dropdown.Link
        href={`${route(column.routeView, {id: value})}`}
        method="get"
        as="button"
      >
        View
      </Dropdown.Link>
      <Dropdown.Link
        href={`${route(column.routeEdit, {id: value})}`}
        method="get"
        as="button"
      >
        Edit
      </Dropdown.Link>
      <Dropdown.Button
        onClick={() => {
          column.showReset(row.original);
        }}
      >
        Reset
      </Dropdown.Button>
      <Dropdown.Button
        onClick={() => {
          column.showDelete(row.original);
        }}
      >
        Delete
      </Dropdown.Button>
    </Dropdown.Content>
  );
}

export default function Users(props) {
  const { data } = usePage().props;
  const dataPagination = TransformData.paginate(data);

  const deleteDialogRef = useRef();
  const resetDialogRef = useRef();

  const showDeleteDialog = (data) => {
    deleteDialogRef.current.updateTitle("Do you want to delete this data?");
    deleteDialogRef.current.updateMessage(data["name"]);
    deleteDialogRef.current.updateData(data);
    deleteDialogRef.current.openDialog();
  };

  const callbackDeleteDialog = (value, data) => {
    if (value) Inertia.delete(route("settings.users.destroy", { id: data["id"] }));
  };

  const showResetDialog = (data) => {
    resetDialogRef.current.updateTitle(
      "Do you want to reset this user password?"
    );
    resetDialogRef.current.updateMessage(data["name"]);
    resetDialogRef.current.updateData(data);
    resetDialogRef.current.openDialog();
  };

  const callbackResetDialog = (value, data) => {
    if (value) Inertia.put(route("settings.users.reset", { id: data["id"] }));
  };

  const columns = useMemo(
    () => [
      {
        Header: "No",
        accessor: "no",
      },
      {
        Header: "Name",
        accessor: "name",
        Cell: AvatarCell,
        imgAccessor: "profile_photo_url",
        emailAccessor: "email",
      },
      {
        Header: "Gender",
        accessor: "profile_gender",
      },
      {
        Header: "Role",
        accessor: "roles",
        Cell: StatusPill,
      },
      {
        Header: "Status",
        accessor: "status",
        Cell: StatusPill,
      },
      {
        Header: "Registered",
        accessor: "created_at",
        Cell: DateCell,
      },
      {
        Header: "Action",
        accessor: "id",
        showDelete: showDeleteDialog,
        showReset: showResetDialog,
        routeView: "settings.users.show",
        routeEdit: "settings.users.edit",
        Cell: ActionCell,
        customOptions: customAction,
      },
    ],
    []
  );

  const fetchData = useCallback(({ pageSize, pageIndex }) => {
    if (
      pageSize !== dataPagination.paging.per_page ||
      pageIndex !== dataPagination.paging.current_page - 1
    )
      Inertia.get(route("settings.users.index"), {
        page: pageIndex + 1,
        perPage: pageSize,
      });
  }, []);

  return (
    <AuthenticatedLayout
      auth={props.auth}
      errors={props.errors}
      pageItems={props.pageItems}
      breadcrumb={
        <div className="flex flex-wrap space-x-4">
          <Link
            as="button"
            href={route("settings.users.create")}
            className="inline-flex items-center px-4 py-2 bg-blue-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-blue-400 transition ease-in-out duration-150"
          >
            Create
          </Link>
          <Link
            as="button"
            href={route("settings.trashed.users.index")}
            className="inline-flex items-center px-4 py-2 bg-red-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-blue-400 transition ease-in-out duration-150"
          >
            {/* <TrashIcon className="h-4 w-4 mr-1" /> */}
            Trashed
          </Link>
        </div>
      }
    >
      <div className="px-4">
        <Table
          columns={columns}
          inputData={dataPagination}
          fetchData={fetchData}
          withNumber={true}
        />
        <YesNoDialog
          ref={deleteDialogRef}
          callback={callbackDeleteDialog}
          bgColor="bg-red-400"
          icon="ExclamationIcon"
        />
        <YesNoDialog
          ref={resetDialogRef}
          callback={callbackResetDialog}
          bgColor="bg-green-400"
        />
      </div>
    </AuthenticatedLayout>
  );
}
