import { Menu, Transition } from "@headlessui/react";
import { Link } from "@inertiajs/inertia-react";
import React from "react";

function classNames(...classes) {
    return classes.filter(Boolean).join(" ");
}

export default function ChildMenu({ items, className }) {
    return (
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
                {items.map((item, itemIndex) => {
                    return (
                        <Link
                            key={`header-menu-child-${itemIndex}`}
                            href={item.href}
                        >
                            {item.name}
                        </Link>
                    );
                })}
            </Menu.Items>
        </Transition>
    );
}
