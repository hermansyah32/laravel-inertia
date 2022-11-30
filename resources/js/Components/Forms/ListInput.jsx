import { Listbox, Transition } from "@headlessui/react";
import { CheckIcon, ChevronDownIcon } from "@heroicons/react/outline";
import React, { Fragment, useRef, useState } from "react";

const getSelectedValue = (value, list) => {
  const result = list.filter(
    (item) => item.id === value || item.name === value
  );

  return result[0] || null;
};

export default function ListInput({
  name,
  list = [],
  value,
  handleChange,
  multiple = false,
}) {
  const listButton = useRef();
  const [selected, setSelected] = useState(getSelectedValue(value, list));
  const [onFocus, setOnFocus] = useState(false)

  const onHandleChange = (value) => {
    handleChange(value);
    setSelected(value);
  };

  return (
    <div className="flex flex-col items-start w-full">
      <Listbox
        value={selected}
        onChange={onHandleChange}
        name={name}
        multiple={multiple}
      >
        <div className="relative mt-1 w-full">
          <div className={`relative w-full`}>
          <Listbox.Button className={`relative w-full cursor-default rounded-md shadow-sm outline outline-1 outline-gray-300 bg-white ${selected ? 'py-2.5' : 'py-5'} pl-3 pr-10 text-left focus:outline focus:outline-2 focus-visible:outline-blue-600 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-600 sm:text-sm`}>
            <span className="block truncate">{selected?.name}</span>
            <span className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
              <ChevronDownIcon
                className="h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
            </span>
          </Listbox.Button>
          </div>
          <Transition
            as={Fragment}
            leave="transition ease-in duration-100"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
          >
            <Listbox.Options className="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
              {list.map((item) => (
                <Listbox.Option
                  key={item.id}
                  className={({ active }) =>
                    `relative cursor-default select-none py-2 pl-10 pr-4 ${
                      active ? "bg-gray-600 text-white" : "text-gray-900"
                    }`
                  }
                  value={item}
                >
                  {({ selected, active }) => (
                    <>
                      <span
                        className={`block truncate ${
                          selected ? "font-medium" : "font-normal"
                        }`}
                      >
                        {item.name}
                      </span>
                      {selected ? (
                        <span
                          className={`absolute inset-y-0 left-0 flex items-center pl-3 ${
                            active ? "text-white" : "text-gray-600"
                          }`}
                        >
                          <CheckIcon className="h-5 w-5" aria-hidden="true" />
                        </span>
                      ) : null}
                    </>
                  )}
                </Listbox.Option>
              ))}
            </Listbox.Options>
          </Transition>
        </div>
      </Listbox>
    </div>
  );
}
