import React, { useState } from "react";
import SidebarContentLogo from "./SidebarContentLogo";

function classes(...classes) {
    return classes.filter(Boolean).join(" ");
}

export default function SidebarContentLeft() {
    const [show, setShow] = useState(true);
    const [tooltipStatus, setTooltipStatus] = useState(0);
    return (
        <div className="hidden md:flex bg-gray-900 pt-4 pb-2 px-4 lg:px-6">
            <div className="flex flex-grow h-full">
                <div className=" flex flex-col h-full justify-between">
                    <div>
                        {/* Sidebar Header */}
                        <div className="flex items-center">
                            <SidebarContentLogo
                                page="dashboard"
                                className="h-9 w-9 fill-white"
                            />

                            {show && (
                                <div
                                    className="pl-3 text-white font-bold text-2xl"
                                    id="closed"
                                >
                                    Dashboard
                                </div>
                            )}
                        </div>
                        {/* /End sidebar Header */}
                        {/* Sidebar minimize button */}
                        <div className="mt-10 flex items-center relative">
                            <div
                                className="-mt-5"
                                onClick={() => setShow(!show)}
                            >
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
                        <div className="flex items-center">
                            <ul aria-orientation="vertical">
                                <li
                                    tabIndex={0}
                                    role="button"
                                    aria-label="Overview"
                                    className="cursor-pointer mt-10"
                                >
                                    <svg
                                        width={20}
                                        height={20}
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M2.5 2.5H9.16667V9.16667H2.5V2.5ZM2.5 10.8333H9.16667V17.5H2.5V10.8333ZM10.8333 2.5H17.5V9.16667H10.8333V2.5ZM10.8333 10.8333H17.5V17.5H10.8333V10.8333ZM12.5 4.16667V7.5H15.8333V4.16667H12.5ZM12.5 12.5V15.8333H15.8333V12.5H12.5ZM4.16667 4.16667V7.5H7.5V4.16667H4.16667ZM4.16667 12.5V15.8333H7.5V12.5H4.16667Z"
                                            fill="#9CA3AF"
                                        />
                                    </svg>
                                </li>
                                <li
                                    tabIndex={0}
                                    role="button"
                                    aria-label="People"
                                    className="cursor-pointer mt-6"
                                >
                                    <svg
                                        width={20}
                                        height={20}
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M1.6665 18.3333C1.6665 16.5652 2.36888 14.8695 3.61913 13.6193C4.86937 12.369 6.56506 11.6667 8.33317 11.6667C10.1013 11.6667 11.797 12.369 13.0472 13.6193C14.2975 14.8695 14.9998 16.5652 14.9998 18.3333H13.3332C13.3332 17.0073 12.8064 15.7355 11.8687 14.7978C10.931 13.8601 9.65925 13.3333 8.33317 13.3333C7.00709 13.3333 5.73532 13.8601 4.79764 14.7978C3.85995 15.7355 3.33317 17.0073 3.33317 18.3333H1.6665ZM8.33317 10.8333C5.57067 10.8333 3.33317 8.59584 3.33317 5.83334C3.33317 3.07084 5.57067 0.833336 8.33317 0.833336C11.0957 0.833336 13.3332 3.07084 13.3332 5.83334C13.3332 8.59584 11.0957 10.8333 8.33317 10.8333ZM8.33317 9.16667C10.1748 9.16667 11.6665 7.675 11.6665 5.83334C11.6665 3.99167 10.1748 2.5 8.33317 2.5C6.4915 2.5 4.99984 3.99167 4.99984 5.83334C4.99984 7.675 6.4915 9.16667 8.33317 9.16667ZM15.2365 12.2525C16.4076 12.7799 17.4015 13.6344 18.0987 14.7131C18.7958 15.7918 19.1666 17.0489 19.1665 18.3333H17.4998C17.5 17.37 17.222 16.4271 16.6991 15.618C16.1762 14.8089 15.4307 14.1681 14.5523 13.7725L15.2357 12.2525H15.2365ZM14.6632 2.84417C15.5028 3.19025 16.2206 3.77795 16.7257 4.53269C17.2307 5.28744 17.5002 6.1752 17.4998 7.08334C17.5002 8.22695 17.0729 9.32936 16.302 10.174C15.531 11.0187 14.4721 11.5446 13.3332 11.6483V9.97084C13.9506 9.8824 14.5235 9.59835 14.9676 9.16038C15.4117 8.72242 15.7037 8.1536 15.8008 7.53745C15.8979 6.92129 15.7948 6.29025 15.5068 5.73696C15.2188 5.18368 14.761 4.73729 14.2007 4.46334L14.6632 2.84417Z"
                                            fill="#9CA3AF"
                                        />
                                    </svg>
                                </li>
                                <li
                                    tabIndex={0}
                                    role="button"
                                    aria-label="Workflow"
                                    className="cursor-pointer mt-6"
                                >
                                    <svg
                                        width={20}
                                        height={20}
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M7.50016 6.2825L12.5002 17.9492L15.5493 10.8333H19.1668V9.16667H14.451L12.5002 13.7175L7.50016 2.05083L4.451 9.16667H0.833496V10.8333H5.54933L7.50016 6.2825Z"
                                            fill="#9CA3AF"
                                        />
                                    </svg>
                                </li>
                                <li
                                    tabIndex={0}
                                    role="button"
                                    aria-label="Campaignns"
                                    className="cursor-pointer mt-6"
                                >
                                    <svg
                                        width={20}
                                        height={20}
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M9.1665 1.70834V3.385C5.87817 3.795 3.33317 6.6 3.33317 10C3.33317 13.6817 6.31817 16.6667 9.99984 16.6667C11.5407 16.6667 12.9582 16.1442 14.0882 15.2667L15.274 16.4525C13.8373 17.6275 11.9998 18.3333 9.99984 18.3333C5.39734 18.3333 1.6665 14.6025 1.6665 10C1.6665 5.67917 4.95567 2.12584 9.1665 1.70834ZM18.2915 10.8333C18.1248 12.5092 17.4632 14.0392 16.4523 15.2733L15.2665 14.0883C15.9832 13.1658 16.4632 12.0508 16.6148 10.8333H18.2923H18.2915ZM10.8348 1.70834C14.7715 2.09917 17.9015 5.23 18.2932 9.16667H16.6157C16.2398 6.15167 13.8498 3.76167 10.8348 3.385V1.7075V1.70834Z"
                                            fill="#9CA3AF"
                                        />
                                    </svg>
                                </li>
                                <li
                                    tabIndex={0}
                                    role="button"
                                    aria-label="Messages"
                                    className="cursor-pointer mt-6"
                                >
                                    <svg
                                        width={20}
                                        height={20}
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M6.07568 17.3533L1.66651 18.3333L2.64651 13.9242C2.00112 12.717 1.66445 11.3689 1.66651 10C1.66651 5.39751 5.39735 1.66667 9.99985 1.66667C14.6023 1.66667 18.3332 5.39751 18.3332 10C18.3332 14.6025 14.6023 18.3333 9.99985 18.3333C8.63098 18.3354 7.28286 17.9987 6.07568 17.3533ZM6.31735 15.5925L6.86151 15.8842C7.82697 16.4001 8.90517 16.669 9.99985 16.6667C11.3184 16.6667 12.6073 16.2757 13.7036 15.5431C14.8 14.8106 15.6545 13.7694 16.159 12.5512C16.6636 11.3331 16.7957 9.99261 16.5384 8.6994C16.2812 7.4062 15.6462 6.21831 14.7139 5.28596C13.7815 4.35361 12.5937 3.71867 11.3004 3.46144C10.0072 3.2042 8.6668 3.33622 7.44862 3.84081C6.23045 4.34539 5.18926 5.19988 4.45672 6.2962C3.72417 7.39253 3.33318 8.68146 3.33318 10C3.33318 11.1117 3.60401 12.1817 4.11651 13.1383L4.40735 13.6825L3.86151 16.1383L6.31735 15.5925Z"
                                            fill="#9CA3AF"
                                        />
                                    </svg>
                                </li>
                                <li
                                    tabIndex={0}
                                    role="button"
                                    aria-label="stack"
                                    className="cursor-pointer mt-6"
                                >
                                    <svg
                                        width={20}
                                        height={20}
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M16.736 12.6667L17.7377 13.2675C17.7995 13.3045 17.8506 13.3569 17.8862 13.4195C17.9217 13.4822 17.9404 13.553 17.9404 13.625C17.9404 13.697 17.9217 13.7678 17.8862 13.8305C17.8506 13.8931 17.7995 13.9455 17.7377 13.9825L10.4294 18.3675C10.2998 18.4454 10.1514 18.4865 10.0002 18.4865C9.849 18.4865 9.70065 18.4454 9.57104 18.3675L2.2627 13.9825C2.20091 13.9455 2.14976 13.8931 2.11424 13.8305C2.07873 13.7678 2.06006 13.697 2.06006 13.625C2.06006 13.553 2.07873 13.4822 2.11424 13.4195C2.14976 13.3569 2.20091 13.3045 2.2627 13.2675L3.26437 12.6667L10.0002 16.7083L16.736 12.6667ZM16.736 8.75001L17.7377 9.35084C17.7995 9.38783 17.8506 9.44022 17.8862 9.50287C17.9217 9.56553 17.9404 9.63632 17.9404 9.70834C17.9404 9.78036 17.9217 9.85115 17.8862 9.91381C17.8506 9.97647 17.7995 10.0288 17.7377 10.0658L10.0002 14.7083L2.2627 10.0658C2.20091 10.0288 2.14976 9.97647 2.11424 9.91381C2.07873 9.85115 2.06006 9.78036 2.06006 9.70834C2.06006 9.63632 2.07873 9.56553 2.11424 9.50287C2.14976 9.44022 2.20091 9.38783 2.2627 9.35084L3.26437 8.75001L10.0002 12.7917L16.736 8.75001ZM10.4285 1.09084L17.7377 5.47584C17.7995 5.51284 17.8506 5.56521 17.8862 5.62787C17.9217 5.69053 17.9404 5.76132 17.9404 5.83334C17.9404 5.90536 17.9217 5.97615 17.8862 6.03881C17.8506 6.10147 17.7995 6.15385 17.7377 6.19084L10.0002 10.8333L2.2627 6.19084C2.20091 6.15385 2.14976 6.10147 2.11424 6.03881C2.07873 5.97615 2.06006 5.90536 2.06006 5.83334C2.06006 5.76132 2.07873 5.69053 2.11424 5.62787C2.14976 5.56521 2.20091 5.51284 2.2627 5.47584L9.57104 1.09084C9.70065 1.01297 9.849 0.971832 10.0002 0.971832C10.1514 0.971832 10.2998 1.01297 10.4294 1.09084H10.4285ZM10.0002 2.77667L4.90604 5.83334L10.0002 8.89001L15.0944 5.83334L10.0002 2.77667Z"
                                            fill="#9CA3AF"
                                        />
                                    </svg>
                                </li>
                                <li
                                    tabIndex={0}
                                    role="button"
                                    aria-label="Notifications"
                                    className="cursor-pointer mt-6"
                                >
                                    <svg
                                        width={20}
                                        height={20}
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M18.3332 16.6667H1.6665V15H2.49984V9.19249C2.49984 5.03582 5.85817 1.66666 9.99984 1.66666C14.1415 1.66666 17.4998 5.03582 17.4998 9.19249V15H18.3332V16.6667ZM4.1665 15H15.8332V9.19249C15.8332 5.95666 13.2215 3.33332 9.99984 3.33332C6.77817 3.33332 4.1665 5.95666 4.1665 9.19249V15ZM7.9165 17.5H12.0832C12.0832 18.0525 11.8637 18.5824 11.473 18.9731C11.0823 19.3638 10.5524 19.5833 9.99984 19.5833C9.4473 19.5833 8.9174 19.3638 8.5267 18.9731C8.136 18.5824 7.9165 18.0525 7.9165 17.5Z"
                                            fill="#9CA3AF"
                                        />
                                    </svg>
                                </li>
                            </ul>
                            {show && (
                                <div className="w-full mt-10">
                                    <p className="text-base leading-4 pl-3 cursor-pointer text-gray-400">
                                        Overview
                                    </p>
                                    <p className="text-base leading-4 pl-3 cursor-pointer pt-7 text-gray-400">
                                        People
                                    </p>
                                    <p className="text-base leading-4 pl-3 cursor-pointer pt-7 text-gray-400">
                                        Workflow
                                    </p>
                                    <p className="text-base leading-4 pl-3 cursor-pointer pt-7 text-gray-400">
                                        Campaignns
                                    </p>
                                    <p className="text-base leading-4 pl-3 cursor-pointer pt-7 text-gray-400">
                                        Messages
                                    </p>
                                    <p className="text-base leading-4 pl-3 cursor-pointer pt-7 text-gray-400">
                                        Stack
                                    </p>
                                    <p className="text-base leading-4 pl-3 cursor-pointer pt-7 text-gray-400">
                                        Notifications
                                    </p>
                                </div>
                            )}
                        </div>
                        {/* /End Sidebar menu */}
                    </div>
                </div>
            </div>
        </div>
    );
}
