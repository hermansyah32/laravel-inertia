import { Menu, Transition } from "@headlessui/react";
import { Link } from "@inertiajs/inertia-react";
import React, { Fragment } from "react";
import Avatar from "./Avatar";

const userNavigation = [
    { type: "link", label: "Profile", href: "#" },
    { type: "sep" },
    { type: "link", label: "Account", href: "#" },
    { type: "link", label: "Logout", href: route('logout'), method: 'post' },
];

function classNames(...classes) {
    return classes.filter(Boolean).join(" ");
}

const renderNavigation = (type, label, href, method, key) => {
    switch (type) {
        case "separator":
            return <div className="px-1 py-1" key={key}></div>;

        default:
            // return all to link type
            return (
                <Menu.Item as={Fragment} key={key}>
                    {({ active }) => (
                        <Link
                            className={classNames(
                                active ? "bg-gray-100" : "",
                                "block px-4 py-2 text-sm text-gray-700"
                            )}
                            href={href}
                            method={method ? method : "get"}
                        >
                            {label}
                        </Link>
                    )}
                </Menu.Item>
            );
    }
};

export default function AvatarMenu({ name, email, role, profilePhoto }) {
    return (
        <Menu as="div" className="relative ml-3">
            <div>
                <Menu.Button className="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                    <span className="sr-only">Open user menu</span>
                    <Avatar source={profilePhoto} name={name} role={role} />
                </Menu.Button>
            </div>
            <Transition
                as={Fragment}
                enter="transition ease-out duration-100"
                enterFrom="transform opacity-0 scale-95"
                enterTo="transform opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="transform opacity-100 scale-100"
                leaveTo="transform opacity-0 scale-95"
            >
                <Menu.Items className="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    {userNavigation.map((item) =>
                        renderNavigation(
                            item.type,
                            item.label,
                            item.href,
                            item.method,
                            item.label
                        )
                    )}
                </Menu.Items>
            </Transition>
        </Menu>
    );
}
