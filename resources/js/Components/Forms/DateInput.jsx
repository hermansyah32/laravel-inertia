import dayjs from "dayjs";
import React, { useState } from "react";
import DatePicker from "react-datepicker";
import "../../../css/react-datepicker.css";

export default function DateInput({ name, value, className, handleChange, inputFormat="YYYY-MM-DD", displayFormat="yyyy-MM-dd"}) {
  const [isFocused, setIsFocused] = useState(false);
  
  return (
    <div className={`flex flex-col items-start w-full ${isFocused ? "relative" : ""}`}>
      <DatePicker
        name={name}
        className={
          `border-gray-300 focus:border-red7-300 focus:ring focus:ring-red7-200 focus:ring-opacity-50 rounded-md shadow-sm ` +
          className
        }
        onFocus={(e) => setIsFocused(true)}
        onBlur={(e) => setIsFocused(false)}
        onChange={(e) => handleChange(e)}
        selected={value instanceof Date ? value : dayjs(value, inputFormat).isValid() ? dayjs(value, inputFormat).toDate() : '' }
        peekNextMonth
        showMonthDropdown
        showYearDropdown
        dropdownMode="select"
        dateFormat={displayFormat}
      />
    </div>
  );
}
