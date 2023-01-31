import React, { useCallback, useMemo, useRef } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Table from "@/Components/Table/Table";
import {
  AvatarCell,
  StatusPill,
  SelectColumnFilter,
  DateCell,
  TrashedActionCell,
} from "@/Components/Table/TableCell";
import { Link, usePage } from "@inertiajs/inertia-react";
import { TransformData } from "@/Helper/Transform";
import { Inertia } from "@inertiajs/inertia";
import YesNoDialog from "@/Components/Dialog/YesNoDialog";

export default function Trashed(props) {
  const { _data } = usePage().props;
  const dataPagination = TransformData.paginate(_data);

  const restoreDialogRef = useRef();
  const deleteDialogRef = useRef();

  const showDeleteDialog = (data) => {
    deleteDialogRef.current.updateTitle(
      "Do you want to permanently delete this data?"
    );
    deleteDialogRef.current.updateMessage(
      `You can't recover this data anymore (${data["name"]})`
    );
    deleteDialogRef.current.updateData(data);
    deleteDialogRef.current.openDialog();
  };

  const showRestoreDialog = (data) => {
    restoreDialogRef.current.updateTitle(
      "Do you want to restore delete this data?"
    );
    restoreDialogRef.current.updateMessage(`${data["name"]}`);
    restoreDialogRef.current.updateData(data);
    restoreDialogRef.current.openDialog();
  };

  const callbackDeleteDialog = (value, data) => {
    if (value)
      Inertia.delete(
        route("settings.trashed.users.destroy", { id: data["id"] })
      );
  };

  const callbackRestoreDialog = (value, data) => {
    if (value)
      Inertia.put(
        route("settings.trashed.users.restore", { id: data["id"] })
      );
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
        accessor: "role",
        Filter: SelectColumnFilter, // new
        filter: "includes",
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
        showRestore: showRestoreDialog,
        routeView: "settings.trashed.users.show",
        routeRestore: "settings.trashed.users.restore",
        Cell: TrashedActionCell,
      },
    ],
    []
  );

  const fetchData = useCallback(({ pageSize, pageIndex }) => {
    if (
      pageSize !== dataPagination.paging.per_page ||
      pageIndex !== dataPagination.paging.current_page - 1
    )
      Inertia.get(route("settings.trashed.users.index"), {
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
          <h1 className="text-lg font-semibold">User</h1>
        </div>
        <div className="inline-flex space-x-4">
          <Link
            as="button"
            href={route("settings.users.index")}
            className="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-800 transition ease-in-out duration-150"
          >
            {/* <TrashIcon className="h-4 w-4 mr-1" /> */}
            Back
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
          ref={restoreDialogRef}
          callback={callbackRestoreDialog}
          bgColor="bg-green-400"
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
