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
  const { data } = usePage().props;
  const dataPagination = TransformData.paginate(data);

  const deleteDialogRef = useRef();

  const showDeleteDialog = (data) => {
    // console.log('data :>> ', data);
    deleteDialogRef.current.updateTitle("Do you want to permanently delete this data?");
    deleteDialogRef.current.updateMessage(`You can't recover this data anymore <br>${data["name"]}`);
    deleteDialogRef.current.updateData(data);
    deleteDialogRef.current.openDialog();
  };

  const callbackDeleteDialog = (value, data) => {
    if (value) Inertia.delete(route("settings.trashed.users.destroy", { id: data["id"] }));
    
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
      pageItems={props.pageItems}
      breadcrumb={
        <div className="flex flex-wrap space-x-4">
          <Link
            as="button"
            href={route("settings.users.index")}
            className="inline-flex items-center px-4 py-2 bg-blue-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-blue-400 transition ease-in-out duration-150"
          >
            Back
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
      </div>
    </AuthenticatedLayout>
  );
}
