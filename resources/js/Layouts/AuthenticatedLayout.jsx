import React from "react";
import { usePage } from "@inertiajs/inertia-react";
import Header from "@/Components/Header";
import Sidebar from "@/Components/Sidebar";
import Content from "@/Components/Content";

export default function AuthenticatedLayout({
  auth,
  errors,
  flash,
  navigationRoutes,
  children,
}) {
  const { component } = usePage();
  return (
    <div className="block h-full">
      <Header user={auth.user} component={component} />
      <div className="flex flex-wrap w-full bg-slate-200">
        <Sidebar navigation={navigationRoutes} />
        <Content flash={flash} children={children} component={component} />
      </div>
    </div>
  );
}
