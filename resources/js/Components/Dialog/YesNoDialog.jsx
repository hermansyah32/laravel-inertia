import { Dialog, Transition } from "@headlessui/react";
import { Fragment, useState } from "react";
import React, { forwardRef, useImperativeHandle } from "react";
import DynamicIconSolid from "../DynamicIconSolid";

const YesNoDialog = forwardRef(
  (
    {
      callback,
      ttl,
      msg,
      show = false,
      cancelable = true,
      bgColor = "bg-blue-300",
      icon = "ExclamationCircleIcon",
    },
    ref
  ) => {
    const [open, setOpen] = useState(show);
    const [title, setTitle] = useState(ttl);
    const [message, setMessage] = useState(msg);
    const [data, setData] = useState([]);

    const openDialog = () => {
      setOpen(true);
    };

    const closeDialog = () => {
      onDialogClose();
    };

    const updateTitle = (ttl) => {
      setTitle(ttl);
    };
    const updateData = (data) => {
      setData(data);
    };

    const updateMessage = (msg) => {
      setMessage(msg);
    };

    useImperativeHandle(ref, () => {
      return {
        openDialog: openDialog,
        closeDialog: closeDialog,
        updateTitle: updateTitle,
        updateMessage: updateMessage,
        updateData: updateData,
      };
    });

    const onDialogClose = () => {
      setOpen(false);
    };

    return (
      <Transition appear show={open} as={Fragment}>
        <Dialog as="div" className="relative z-10" onClose={onDialogClose}>
          <Transition.Child
            as={Fragment}
            enter="ease-out duration-300"
            enterFrom="opacity-0"
            enterTo="opacity-100"
            leave="ease-in duration-200"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
          >
            <div className="fixed inset-0 bg-black bg-opacity-25" />
          </Transition.Child>

          <div className="fixed inset-0 overflow-y-auto">
            <div className="flex min-h-full items-center justify-center p-4 text-center">
              <Transition.Child
                as={Fragment}
                enter="ease-out duration-300"
                enterFrom="opacity-0 scale-95"
                enterTo="opacity-100 scale-100"
                leave="ease-in duration-200"
                leaveFrom="opacity-100 scale-100"
                leaveTo="opacity-0 scale-95"
              >
                <Dialog.Panel className="w-full max-w-md transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-xl transition-all text-black">
                  <Dialog.Title
                    as="h3"
                    className={`text-lg font-medium p-4 leading-6 text-gray-900 select-none flex flex-row items-center ${bgColor}`}
                  >
                    <div className="w-full">{title}</div>
                    <DynamicIconSolid iconName={icon} className="h-8 w-8" />
                  </Dialog.Title>
                  <div className="mt-2 select-none px-4">
                    <p className="text-gray-500">{message}</p>
                  </div>
                  <div className="mt-4 flex justify-between px-4 pb-4">
                    <button
                      type="button"
                      className="inline-flex justify-center rounded-md border border-transparent bg-red-400 px-4 py-2 text-sm font-medium text-white hover:opacity-25 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2"
                      onClick={(e) => {
                        if (callback) {
                          callback(true, data);
                        }
                        closeDialog();
                      }}
                    >
                      Yes
                    </button>
                    <button
                      type="button"
                      className="inline-flex justify-center rounded-md border border-transparent bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:opacity-25 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2"
                      onClick={(e) => {
                        if (callback) {
                          callback(false, data);
                        }
                        closeDialog();
                      }}
                    >
                      Cancel
                    </button>
                  </div>
                </Dialog.Panel>
              </Transition.Child>
            </div>
          </div>
        </Dialog>
      </Transition>
    );
  }
);

export default YesNoDialog;
