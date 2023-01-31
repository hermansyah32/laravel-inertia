import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function NotFound(props) {
  
  return (
    <AuthenticatedLayout auth={props.auth} errors={props.errors} navigationRoutes={props.navigationRoutes}>
      <div className="block w-full">
        <div className="text-bold text-xl text-center">
          Not Found
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
