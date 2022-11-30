import { Combobox, Transition } from "@headlessui/react";
import { CheckIcon, ChevronDownIcon } from "@heroicons/react/outline";
import React, { Fragment, useRef, useState } from "react";

const getSelectedValue = (value, list) => {
  const result = list.filter(
    (item) => item.id === value || item.name === value
  );
  
  return result[0] || null;
};

export default function ComboInput({ name, list = [], value, handleChange, multiple = false }) {
  const comboButton = useRef()
  const [selected, setSelected] = useState(getSelectedValue(value, list));
  const [query, setQuery] = useState("");

  const onHandleChange = (value) => {
    handleChange(value);
    setSelected(value);
  };

  const filteredList =
    query === ""
      ? list
      : list.filter((item) =>
          item.name
            .toLowerCase()
            .replace(/\s+/g, "")
            .includes(query.toLowerCase().replace(/\s+/g, ""))
        );

  return (
    <div className="flex flex-col items-start w-full">
      <Combobox value={selected} onChange={onHandleChange} name={name} multiple={multiple}>
        <div className="relative mt-1 w-full">
          <div className={`relative w-full cursor-default`}>
            <Combobox.Input
              className="w-full border-gray-300 focus:border-red7-300 focus:ring focus:ring-red7-200 focus:ring-opacity-50 rounded-md shadow-sm pr-8"
              displayValue={(item) => item ? item.name : ''}
              onChange={(event) => setQuery(event.target.value)}
              onFocus={(e) => comboButton.current.click()}
            />
            <Combobox.Button ref={comboButton} className="absolute inset-y-0 right-0 flex items-center pr-2">
              <ChevronDownIcon
                className="h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
            </Combobox.Button>
          </div>
          <Transition
            as={Fragment}
            leave="transition ease-in duration-100"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
            afterLeave={() => setQuery("")}
          >
            <Combobox.Options className="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
              {filteredList.length === 0 && query !== "" ? (
                <div className="relative cursor-default select-none py-2 px-4 text-gray-700">
                  Nothing found.
                </div>
              ) : (
                filteredList.map((item) => (
                  <Combobox.Option
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
                  </Combobox.Option>
                ))
              )}
            </Combobox.Options>
          </Transition>
        </div>
      </Combobox>
    </div>
  );
}
