import React, { useEffect } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { useForm } from "@inertiajs/inertia-react";
import InputLabel from "@/Components/Forms/InputLabel";
import TextInput from "@/Components/Forms/TextInput";
import InputError from "@/Components/Forms/InputError";
import PrimaryButton from "@/Components/PrimaryButton";

export default function Security(props) {
  const { data, setData, put, processing, errors, reset } = useForm({
    current_password: "",
    password: "",
    confirm_password: "",
  });

  const onInputHandleChange = (event) => {
    let value = event.target.value;
    if (event.target.type === "checkbox" || event.target.type === "radio") {
      value = event.target.checked;
    }

    setData(event.target.name, value);
  };

  const submit = (e) => {
    e.preventDefault();
    put(route("account.security.update"), data);
  };

  return (
    <AuthenticatedLayout
      auth={props.auth}
      errors={props.errors}
      pageItems={props.pageItems}
      flash={props.flash}
    >
      <form onSubmit={submit}>
        <div className="px-4">
          <div className="block md:flex flex-row">
            <InputLabel
              className="md:w-1/4 md:self-center md:font-bold"
              forInput="current_password"
              value="Current Password"
            />
            <div className="block w-full">
              <TextInput
                name="current_password"
                type="password"
                className="mt-1 block w-full"
                isFocused={true}
                handleChange={onInputHandleChange}
              />
              <InputError message={errors.current_password} className="mt-2" />
            </div>
          </div>
          <div className="block md:flex flex-row">
            <InputLabel
              className="md:w-1/4 md:self-center md:font-bold"
              forInput="password"
              value="Password"
            />
            <div className="block w-full">
              <TextInput
                name="password"
                type="password"
                className="mt-1 block w-full"
                isFocused={true}
                handleChange={onInputHandleChange}
              />
              <InputError message={errors.password} className="mt-2" />
            </div>
          </div>
          <div className="block md:flex flex-row">
            <InputLabel
              className="md:w-1/4 md:self-center md:font-bold"
              forInput="confirm_password"
              value="Confirm Password"
            />
            <div className="block w-full">
              <TextInput
                name="confirm_password"
                type="password"
                className="mt-1 block w-full"
                isFocused={true}
                handleChange={onInputHandleChange}
              />
              <InputError message={errors.confirm_password} className="mt-2" />
            </div>
          </div>
        </div>
        <PrimaryButton className="ml-4 bg-blue-400" processing={processing}>
          Update
        </PrimaryButton>
      </form>
    </AuthenticatedLayout>
  );
}
