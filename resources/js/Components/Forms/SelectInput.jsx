import React, { useEffect, useRef } from "react";

export default function SelectInput({
  name,
  value,
  className,
  list,
  required,
  isFocused,
  handleChange,
}) {
  const input = useRef();

  useEffect(() => {
    if (isFocused) {
      input.current.focus();
    }
  }, []);

  return (
    <div className="flex flex-col items-start">
      <select
        name={name}
        defaultValue={value}
        className={
          `border-gray-300 focus:border-red7-300 focus:ring focus:ring-red7-200 focus:ring-opacity-50 rounded-md shadow-sm ` +
          className
        }
        ref={input}
        required={required}
        onChange={(e) => handleChange(e)}
      >
        {list.map((item, index) => {
          return <option key={index} value={item.id}>{item.name}</option>;
        })}
      </select>
    </div>
  );
}
