import React, { useEffect, useRef } from "react";

export default function FileInput({
  name,
  value,
  className,
  autoComplete,
  required,
  isFocused,
  handleChange,
  multiple = false,
  disabled = false
}) {
  const input = useRef();

  useEffect(() => {
    if (isFocused) {
      input.current.focus();
    }
  }, []);

  return (
    <div className="flex flex-col items-start w-full">
      <input
        type="file"
        name={name}
        defaultValue={value}
        className={
          `border-solid border-gray-300 focus:border-red7-300 focus:ring focus:ring-red7-200 focus:ring-opacity-50 rounded-md shadow-sm ` +
          className
        }
        ref={input}
        autoComplete={autoComplete}
        required={required}
        onChange={(e) => handleChange(e)}
        disabled={disabled}
        multiple={multiple}
      />
    </div>
  );
}
