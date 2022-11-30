import React, { useState, useEffect } from "react";
import { Disclosure } from "@headlessui/react";
import { MenuIcon, BellIcon, XIcon } from "@heroicons/react/outline";
import ApplicationLogo from "@/Components/ApplicationLogo";
import SidebarContentLeft from "@/Components/SidebarContentLeft";
import FooterContent from "@/Components/FooterContent";
import AvatarMenu from "@/Components/AvatarMenu";
import { Link, usePage } from "@inertiajs/inertia-react";
import { navigation, getNavigation } from "@/Helper/Navigation";
import Notification from "@/Components/Notification/Notification";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

export default function AuthenticatedLayout({
  auth,
  errors,
  flash,
  pageItems,
  breadcrumb,
  children,
}) {
  const { component } = usePage();
  return (
    <>
      <div className="h-screen flex flex-col">
        <Disclosure as="nav" className="bg-gray-800">
          {({ open }) => (
            <>
              <div className="px-4 lg:px-6">
                <div className="flex h-16 items-center justify-between">
                  <div className="flex items-center">
                    <div className="-ml-2 flex md:hidden z-10">
                      {/* Mobile menu button */}
                      <Disclosure.Button className="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                        <span className="sr-only">Open main menu</span>
                        {open ? (
                          <XIcon className="block h-6 w-6" aria-hidden="true" />
                        ) : (
                          <MenuIcon
                            className="block h-6 w-6"
                            aria-hidden="true"
                          />
                        )}
                      </Disclosure.Button>
                    </div>
                    <div className="hidden md:flex md:flex-shrink-0">
                      <ApplicationLogo className="h-8 w-8 fill-white" />
                    </div>
                    <div className="hidden md:block">
                      <div className="ml-10 flex items-baseline space-x-4">
                        {navigation.map((item, itemIndex) => {
                          if (item.show)
                            return (
                              <Link
                                key={itemIndex}
                                href={item.url}
                                className={classNames(
                                  component.startsWith(item.label)
                                    ? "bg-gray-900 text-white"
                                    : "text-gray-300",
                                  "px-3 py-2 rounded-md text-sm font-medium  hover:bg-gray-700 hover:text-white "
                                )}
                                as="button"
                              >
                                {item.label}
                              </Link>
                            );
                        })}
                      </div>
                    </div>
                  </div>
                  <div className="absolute left-0 w-full md:static md:-ml-2 md:w-auto flex items-center">
                    <ApplicationLogo className="block h-8 w-auto mx-auto md:hidden fill-white" />
                  </div>
                  <div className="block">
                    <div className="ml-4 flex items-center md:ml-6">
                      <button
                        type="button"
                        className="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                      >
                        <span className="sr-only">View notifications</span>
                        <BellIcon className="h-6 w-6" aria-hidden="true" />
                      </button>

                      <AvatarMenu
                        name={auth.user.name}
                        email={auth.user.email}
                        role={auth.user.type}
                        profilePhoto={auth.user.profile_photo_url}
                      />
                    </div>
                  </div>
                </div>
              </div>

              <Disclosure.Panel className="md:hidden">
                <div className="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                  {navigation.map((item) => {
                    if (item.show) {
                      return (
                        <Disclosure.Button
                          key={item.label}
                          as="a"
                          href={item.href}
                          className={classNames(
                            component.startsWith(item.label)
                              ? "bg-gray-900 text-white"
                              : "text-gray-300 hover:bg-gray-700 hover:text-white",
                            "block px-3 py-2 rounded-md text-base font-medium"
                          )}
                          aria-current={
                            component.startsWith(item.label)
                              ? "page"
                              : undefined
                          }
                        >
                          {item.label}
                        </Disclosure.Button>
                      );
                    }
                  })}
                </div>
              </Disclosure.Panel>
            </>
          )}
        </Disclosure>

        {/* Sidebar left menu on mobile laout */}
        {/* <SidebarLeft /> */}
        {/* /End sidebar left menu */}
        <div className="flex flex-1 overflow-hidden">
          <SidebarContentLeft
            page={getNavigation(component)}
            pageItems={pageItems}
          />
          <div className="flex flex-1 flex-col">
            <header className="bg-white">
              <div className="flex flex-row py-4 px-8 lg:pr-6 shadow">
                <h1 className="flex-grow text-xl font-bold tracking-tight text-gray-900">
                  {getNavigation(component).label}
                </h1>
                {breadcrumb}
              </div>
            </header>
            <Notification flash={flash} />
            <main className="flex">
              <div className="w-full py-6 px-4 lg:pr-6 ">
                {/* Replace with your content */}
                {children}
                {/* /End replace */}
              </div>
            </main>
            <FooterContent />
          </div>
        </div>
      </div>
    </>
  );
}
