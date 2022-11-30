import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Overview(props) {
  
  return (
    <AuthenticatedLayout auth={props.auth} errors={props.errors} pageItems={props.pageItems}>
      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 bg-white border-b border-gray-200">
              Dashboard Page
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
