import { Dialog, Transition } from "@headlessui/react";
import { Fragment, useState } from "react";
import React, { forwardRef, useImperativeHandle } from "react";

const MessageDialog = forwardRef(
  (
    { ttl, msg, show = false, cancelable = true, bgColor = "bg-blue-300" },
    ref
  ) => {
    const [open, setOpen] = useState(show);
    const [title, setTitle] = useState(ttl);
    const [message, setMessage] = useState(msg);

    const openDialog = () => {
      setOpen(true);
    };

    const closeDialog = () => {
      setOpen(false);
      onDialogClose();
    };

    const updateTitle = (ttl) => {
      setTitle(ttl);
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
      };
    });

    const onDialogClose = () => {
      if (cancelable) setOpen(false);
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
                    className="text-lg font-medium p-6 leading-6 text-gray-900 select-none bgColor flex flex-row items-center"
                  >
                    {title}
                  </Dialog.Title>
                  <div className="mt-2 select-none">
                    <p className="text-sm text-gray-500">{message}</p>
                  </div>
                  <div className="mt-4 flex justify-between">
                    <button
                      type="button"
                      className="inline-flex justify-center rounded-md border border-transparent bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:opacity-25 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2"
                      onClick={closeDialog}
                    >
                      OK
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

export default MessageDialog;
