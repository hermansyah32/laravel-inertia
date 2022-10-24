import React, { Fragment } from "react";
import { Popover, Transition } from "@headlessui/react";
import { Bars3Icon, XMarkIcon } from "@heroicons/react/24/outline";
import { Link } from "@inertiajs/inertia-react";
import ApplicationLogo from "../ApplicationLogo";

export default function Navbar({ auth }) {
    return (
        <Popover className="relative bg-white">
            <div className="px-4 sm:px-6 md:px-6 xl:px-20">
                <div className="flex items-center justify-between border-b-2 border-gray-100 py-6 md:justify-start md:space-x-10">
                    <div className="flex justify-start">
                        <Link href="\">
                            <span className="sr-only">Inertia</span>
                            <ApplicationLogo className={"h-8 w-auto sm:h-10"} />
                        </Link>
                    </div>
                    <div className="-my-2 -mr-2 md:hidden">
                        <Popover.Button className="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <span className="sr-only">Open menu</span>
                            <Bars3Icon className="h-6 w-6" aria-hidden="true" />
                        </Popover.Button>
                    </div>
                    <Popover.Group
                        as="nav"
                        className="hidden space-x-10 md:flex"
                    >
                        <Link
                            href={route("welcome")}
                            className="text-base font-medium text-gray-500 hover:text-gray-900"
                        >
                            Beranda
                        </Link>
                        <Link
                            href={route("about")}
                            className="text-base font-medium text-gray-500 hover:text-gray-900"
                        >
                            Tentang
                        </Link>
                    </Popover.Group>
                    <div className="hidden items-center justify-end md:flex md:flex-1 lg:w-0">
                        <Link
                            href={
                                auth.user ? route("dashboard") : route("login")
                            }
                            className="ml-8 inline-flex items-center justify-center whitespace-nowrap rounded-md border border-transparent bg-gray-800 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-gray-600"
                        >
                            {auth.user ? "Dashboard" : "Login"}
                        </Link>
                    </div>
                </div>
            </div>

            <Transition
                as={Fragment}
                enter="duration-200 ease-out"
                enterFrom="opacity-0 scale-95"
                enterTo="opacity-100 scale-100"
                leave="duration-100 ease-in"
                leaveFrom="opacity-100 scale-100"
                leaveTo="opacity-0 scale-95"
            >
                <Popover.Panel
                    focus
                    className="absolute inset-x-0 top-0 origin-top-right transform p-2 transition md:hidden z-10"
                >
                    <div className="divide-y-2 divide-gray-50 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                        <div className="px-5 pt-5 pb-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <ApplicationLogo className={"h-8 w-auto"} />
                                </div>
                                <div className="-mr-2">
                                    <Popover.Button className="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                        <span className="sr-only">
                                            Close menu
                                        </span>
                                        <XMarkIcon
                                            className="h-6 w-6"
                                            aria-hidden="true"
                                        />
                                    </Popover.Button>
                                </div>
                            </div>
                            <div className="mt-6">
                                <nav className="grid gap-y-8">
                                    <Link
                                        href="#"
                                        className="-m-3 flex items-center rounded-md p-3 hover:bg-gray-50"
                                    >
                                        <span className="ml-3 text-base font-medium text-gray-900">
                                            Beranda
                                        </span>
                                    </Link>
                                    <Link
                                        href="#"
                                        className="-m-3 flex items-center rounded-md p-3 hover:bg-gray-50"
                                    >
                                        <span className="ml-3 text-base font-medium text-gray-900">
                                            Tentang
                                        </span>
                                    </Link>
                                </nav>
                            </div>
                        </div>
                        <div className="space-y-6 py-6 px-5">
                            <div>
                                <Link
                                    href={
                                        auth.user
                                            ? route("dashboard")
                                            : route("login")
                                    }
                                    className="flex w-full items-center justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-gray-600"
                                >
                                    {auth.user ? "Dashboard" : "Login"}
                                </Link>
                            </div>
                        </div>
                    </div>
                </Popover.Panel>
            </Transition>
        </Popover>
    );
}
