import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Link, useForm, usePage } from "@inertiajs/inertia-react";
import InputLabel from "@/Components/Forms/InputLabel";
import TextInput from "@/Components/Forms/TextInput";
import InputError from "@/Components/Forms/InputError";
import TextArea from "@/Components/Forms/TextArea";
import DateInput from "@/Components/Forms/DateInput";
import dayjs from "dayjs";
import { TransformConstants } from "@/Helper/Transform";
import PrimaryButton from "@/Components/PrimaryButton";
import FileInput from "@/Components/Forms/FileInput";
import NumberInput from "@/Components/Forms/NumberInput";
import ListInput from "@/Components/Forms/ListInput";

export default function Create(props) {
  const { _constants } = usePage().props;
  const { data, setData, post, processing, progress, errors } = useForm({
    name: "",
    email: "",
    profile_gender: "",
    profile_birthday: "",
    profile_phone: "",
    profile_address: "",
    profile_photo_url: "",
  });

  const onInputHandleChange = (event) => {
    let value = event.target.value;
    if (event.target.type === "checkbox" || event.target.type === "radio") {
      value = event.target.checked;
    }

    if (event.target.type === "file") {
      value = event.target.files[0];
    }
    setData(event.target.name, value);
  };

  const onPhoneHandleChange = (event) => {
    const value = event.target.value.replace(/ /g, "");
    setData("profile_phone", value);
  };

  const onGenderHandleChange = (value) => {
    setData("profile_gender", value.id);
  };

  const onBirthdayHandleChange = (date) => {
    setData("profile_birthday", dayjs(date).format("YYYY-MM-DD"));
  };

  const submit = (e) => {
    e.preventDefault();
    post(route("settings.users.store"), data, { forceFormData: true });
  };

  return (
    <AuthenticatedLayout
      auth={props.auth}
      errors={props.errors}
      navigationRoutes={props.navigationRoutes}
    >
      <div className="bg-white px-4 py-4 inline-flex justify-between w-full items-center">
        <div className="flex flex-wrap">
          <h1 className="text-lg font-semibold">User</h1>
        </div>
        <div>
          <Link
            as="button"
            href={route("settings.users.index")}
            className="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-800 transition ease-in-out duration-150"
          >
            Back
          </Link>
        </div>
      </div>
      <div className="py-6 px-4 w-full">
        <form onSubmit={submit}>
          <div className="block md:flex flex-row mt-4">
            <InputLabel
              forInput="name"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Fullname"
            />
            <div className="block w-full">
              <TextInput
                type="text"
                name="name"
                value={data.name}
                className="mt-1 block w-full"
                isFocused={true}
                handleChange={onInputHandleChange}
              />
              <InputError message={errors.name} className="mt-2" />
            </div>
          </div>
          <div className="block md:flex flex-row mt-4">
            <InputLabel
              forInput="name"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Email"
            />
            <div className="block w-full">
              <TextInput
                type="email"
                name="email"
                value={data.email}
                className="mt-1 block w-full"
                isFocused={false}
                handleChange={onInputHandleChange}
              />
              <InputError message={errors.email} className="mt-2" />
              <InputError message={errors.username} className="mt-2" />
            </div>
          </div>
          <div className="block md:flex flex-row mt-4">
            <InputLabel
              forInput="profile_gender"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Gender"
            />
            <div className="block w-full">
              <ListInput
                name="profile_gender"
                list={TransformConstants.list(_constants).gender || []}
                className="mt-1 block w-full"
                isFocused={false}
                handleChange={onGenderHandleChange}
              />
              <InputError message={errors.profile_gender} className="mt-2" />
            </div>
          </div>
          <div className="block md:flex flex-row mt-4">
            <InputLabel
              forInput="profile_birthday"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Birthday"
            />
            <div className="block w-full">
              <DateInput
                type="text"
                name="profile_birthday"
                value={data.profile_birthday}
                className="mt-1 block w-full"
                isFocused={false}
                handleChange={onBirthdayHandleChange}
              />
              <InputError message={errors.profile_birthday} className="mt-2" />
            </div>
          </div>
          <div className="block md:flex flex-row mt-4">
            <InputLabel
              forInput="profile_phone"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Phone"
            />
            <div className="block w-full">
              <NumberInput
                type="phone"
                value={data.profile_phone}
                className="mt-1 block w-full"
                handleChange={onPhoneHandleChange}
              />
              <InputError message={errors.profile_phone} className="mt-2" />
            </div>
          </div>
          <div className="block md:flex flex-row mt-4">
            <InputLabel
              forInput="profile_address"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Address"
            />
            <div className="block w-full">
              <TextArea
                name="profile_address"
                value={data.profile_address}
                className="mt-1 block w-full"
                isFocused={false}
                handleChange={onInputHandleChange}
              />
              <InputError message={errors.profile_address} className="mt-2" />
            </div>
          </div>
          <div className="block md:flex flex-row">
            <InputLabel
              forInput="profile_photo_url"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Photo"
            />
            <div className="block w-full mt-4">
              <FileInput
                type="text"
                name="profile_photo_url"
                className="mt-1 block w-full"
                isFocused={false}
                handleChange={onInputHandleChange}
              />
              {progress && (
                <progress value={progress.percentage} max="100">
                  {progress.percentage}%
                </progress>
              )}
              <InputError message={errors.profile_photo_url} className="mt-2" />
            </div>
          </div>
          <PrimaryButton className="bg-blue-400" processing={processing}>
            Save
          </PrimaryButton>
        </form>
      </div>
    </AuthenticatedLayout>
  );
}
