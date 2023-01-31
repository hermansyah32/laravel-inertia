import React, { useCallback, useMemo, useRef } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Table from "@/Components/Table/Table";
import {
  AvatarCell,
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
        href={`${route(column.routeView, { id: value })}`}
        method="get"
        as="button"
      >
        View
      </Dropdown.Link>
      <Dropdown.Link
        href={`${route(column.routeEdit, { id: value })}`}
        method="get"
        as="button"
      >
        Edit
      </Dropdown.Link>
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

export default function StudentGrade(props) {
  const { _data } = usePage().props;
  const dataPagination = TransformData.paginate(_data);

  const deleteDialogRef = useRef();

  const showDeleteDialog = (data) => {
    deleteDialogRef.current.updateTitle("Do you want to delete this data?");
    deleteDialogRef.current.updateMessage(data["name"]);
    deleteDialogRef.current.updateData(data);
    deleteDialogRef.current.openDialog();
  };

  const callbackDeleteDialog = (value, data) => {
    if (value)
      Inertia.delete(route("apps.studentgrades.destroy", { id: data["id"] }));
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
        Header: "Action",
        accessor: "id",
        showDelete: showDeleteDialog,
        routeView: "apps.studentgrades.show",
        routeEdit: "apps.studentgrades.edit",
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
      Inertia.get(route("apps.studentgrades.index"), {
        page: pageIndex + 1,
        perPage: pageSize,
      });
  }, []);

  return (
    <AuthenticatedLayout
      auth={props.auth}
      errors={props.errors}
      navigationRoutes={props.navigationRoutes}
    >
      <div className="bg-white px-4 py-4 inline-flex justify-between w-full items-center">
        <div className="flex flex-wrap">
          <h1 className="text-lg font-semibold">Student Grade</h1>
        </div>
        <div className="inline-flex space-x-4">
          <Link
            as="button"
            href={route("apps.studentgrades.create")}
            className="inline-flex items-center px-4 py-2 bg-blue-400 border border-transparent rounded-md font-semibold text-xs justify-center text-white uppercase tracking-widest active:bg-blue-400 transition ease-in-out duration-150"
          >
            Create
          </Link>
          <Link
            as="button"
            href={route("settings.trashed.studentgrades.index")}
            className="inline-flex items-center px-4 py-2 bg-red-400 border border-transparent rounded-md font-semibold text-xs justify-center text-white uppercase tracking-widest active:bg-blue-400 transition ease-in-out duration-150"
          >
            {/* <TrashIcon className="h-4 w-4 mr-1" /> */}
            Trashed
          </Link>
        </div>
      </div>
      <div className="py-6 px-4 w-full">
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
      </div>
    </AuthenticatedLayout>
  );
}
