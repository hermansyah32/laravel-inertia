import { XIcon } from "@heroicons/react/solid";
import React, { useRef, useState } from "react";
import MessageDialog from "../Dialog/MessageDialog";
import DynamicIconOutline from "../DynamicIconOutline";
import MessageToast from "../Toast/MessageToast";

const Banner = ({ flash }) => {
  const [show, setShow] = useState(true);

  let bgColor = "bg-blue-300";
  let icon = "ExclamationCircleIcon";
  switch (flash.notification) {
    case "success":
      bgColor = "bg-green-300";
      icon = "CheckCircleIcon";
      break;
    case "error":
      bgColor = "bg-red-300";
      icon = "ExclamationIcon";
      break;
    case "warning":
      bgColor = "bg-yellow-300";
      icon = "ExclamationIcon";
      break;

    default:
      break;
  }

  const handleClose = (e) => {
    setShow(false);
  };
  return (
    <div
      className={`${
        show ? "flex" : "hidden"
      } relative flex-row items-center px-8 py-4 ${bgColor}`}
    >
      <DynamicIconOutline iconName={icon} className="h-8 w-8 outline-white" />
      <div className="flex-grow align-middle mx-2">
        {flash.title}
        <br></br>
        {flash.message}
      </div>
      <button
        className={`border border-transparent rounded-md transition ease-in-out duration-150 active:opacity-70`}
        onClick={handleClose}
      >
        <XIcon className="h-8 w-8 fill-gray-600" />
      </button>
    </div>
  );
};

const Toast = ({ flash }) => {
  let bgColor = "border-blue-300";
  let icon = "ExclamationCircleIcon";
  switch (flash.notification) {
    case "success":
      bgColor = "border-green-300";
      icon = "CheckCircleIcon";
      break;
    case "error":
      bgColor = "border-red-300";
      icon = "ExclamationIcon";
      break;
    case "warning":
      bgColor = "border-yellow-300";
      icon = "ExclamationIcon";
      break;

    default:
      break;
  }

  return <MessageToast title={flash.title} message={flash.message} bgColor={bgColor} icon={icon} />;
};

const Modal = ({ flash }) => {
  const messageRef = useRef();

  let bgColor = "bg-blue-300";
  let icon = "ExclamationCircleIcon";
  switch (flash.notification) {
    case "success":
      bgColor = "bg-green-300";
      icon = "CheckCircleIcon";
      break;
    case "error":
      bgColor = "bg-red-300";
      icon = "ExclamationIcon";
      break;
    case "warning":
      bgColor = "bg-yellow-300";
      icon = "ExclamationIcon";
      break;

    default:
      break;
  }

  return (
    <MessageDialog ttl={flash.title} msg={flash.message} show={true} bgColor={bgColor} icon={icon} ref={messageRef} />
  );
};

const Notification = ({ flash }) => {
  if (!flash) return <></>;

  if (flash.type?.toLowerCase() === "banner") {
    return <Banner flash={flash} />;
  }

  if (flash.type?.toLowerCase() === "toast") {
    return <Toast flash={flash} />;
  }

  if (flash.type?.toLowerCase() === "modal") {
    return <Modal flash={flash} />;
  }
};

export default Notification;
