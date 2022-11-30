import { Link, usePage } from "@inertiajs/inertia-react";
import React, { useState, useEffect } from "react";
import DynamicIconOutline from "./DynamicIconOutline";
import DynamicIconSolid from "./DynamicIconSolid";

function classes(...classes) {
  return classes.filter(Boolean).join(" ");
}

export default function SidebarContentLeft({ page, pageItems }) {
  const [show, setShow] = useState(true);
  const { url, component } = usePage();

  useEffect(() => {
    // console.log("component :>> ", component);
  }, []);

  return (
    <div className="hidden md:flex bg-gray-900 pt-4 pb-2 px-4 lg:px-6">
      <div className="flex flex-grow h-full">
        <div className=" flex flex-col h-full justify-between">
          <div>
            {/* Sidebar Header */}
            <div className="flex items-center">
              <DynamicIconSolid
                iconName={page.icon}
                className="h-9 w-9 fill-white"
              />

              {show && (
                <div className="pl-3 text-white font-bold text-2xl" id="closed">
                  {page.label}
                </div>
              )}
            </div>
            {/* /End sidebar Header */}
            {/* Sidebar minimize button */}
            <div className="mt-10 flex items-center relative">
              <div className="-mt-5" onClick={() => setShow(!show)}>
                {show ? (
                  <button
                    aria-label="minimize sidebar"
                    id="close"
                    className="w-6 h-6 right-0 -mr-7 bg-red-600 absolute shadow rounded-full flex items-center justify-center cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-700"
                  >
                    <svg
                      width={16}
                      height={16}
                      viewBox="0 0 16 16"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M10 4L6 8L10 12"
                        stroke="white"
                        strokeWidth="1.25"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      />
                    </svg>
                  </button>
                ) : (
                  <button
                    id="open"
                    className=" w-6 h-6 right-0 -mr-7 bg-red-600 absolute shadow rounded-full flex items-center justify-center cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-700"
                  >
                    <svg
                      aria-label="expand sidebar"
                      width={16}
                      height={16}
                      viewBox="0 0 16 16"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M6 12L10 8L6 4"
                        stroke="white"
                        strokeWidth="1.25"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      />
                    </svg>
                  </button>
                )}
              </div>
            </div>
            {/* /End sidebar minimize button */}
            {/* Sidebar menu */}
            <div className="block">
              {pageItems.map((subPage, index) => {
                if (subPage.show) {
                  return (
                    <Link
                      key={index}
                      href={subPage.url}
                      className={classes(
                        show && "w-48",
                        component.startsWith(subPage.component)
                          ? "bg-gray-800"
                          : "bg-gray-900",
                        "flex rounded-md text-slate-200 hover:bg-gray-700 px-2 py-2 mb-2"
                      )}
                      as="button"
                    >
                      <DynamicIconOutline
                        iconName={subPage.icon}
                        className="h-6 w-6 stroke-slate-200"
                      />
                      {show && (
                        <label className="ml-3 cursor-pointer">
                          {subPage.label}
                        </label>
                      )}
                    </Link>
                  );
                }
              })}
            </div>
            {/* /End Sidebar menu */}
          </div>
        </div>
      </div>
    </div>
  );
}
