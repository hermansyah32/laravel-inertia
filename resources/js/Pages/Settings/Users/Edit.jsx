import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Link, useForm, usePage } from "@inertiajs/inertia-react";
import InputLabel from "@/Components/Forms/InputLabel";
import TextInput from "@/Components/Forms/TextInput";
import dayjs from "dayjs";
import Avatar from "@/Components/Avatar";
import TextArea from "@/Components/Forms/TextArea";
import { TransformConstants } from "@/Helper/Transform";
import ListInput from "@/Components/Forms/ListInput";
import DateInput from "@/Components/Forms/DateInput";
import NumberInput from "@/Components/Forms/NumberInput";
import InputError from "@/Components/Forms/InputError";
import FileInput from "@/Components/Forms/FileInput";
import PrimaryButton from "@/Components/PrimaryButton";

export default function Show(props) {
  const { _data, _constants } = usePage().props;
  const { data, setData, put, processing, progress, errors } = useForm({
    name: _data.name || "",
    username: _data.username || "",
    email: _data.email || "",
    profile_gender: _data.profile_gender
      ? TransformConstants.valueOf(_constants.gender, _data.profile_gender)
      : "",
    profile_birthday: _data.profile_birthday
      ? dayjs(_data.profile_birthday, "YYYY-MM-DD").format("DD/MM/YYYY")
      : "",
    profile_address: _data.profile_address || "",
    profile_phone: _data.profile_phone || "",
    profile_photo_url: _data.profile_photo_url || "",
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
    put(route("settings.users.update", { id: _data.id }), data, {
      forceFormData: true,
    });
  };

  return (
    <AuthenticatedLayout
      auth={props.auth}
      errors={props.errors}
      navigationRoutes={props.navigationRoutes}
      flash={props.flash}
    >
      <div className="bg-white px-4 py-4 inline-flex justify-between w-full items-center">
        <div className="flex flex-wrap">
          <h1 className="text-lg font-semibold">User</h1>
        </div>
        <div className="inline-flex space-x-4">
          <Link
            as="button"
            href={route("settings.users.show", { id: _data.id })}
            className="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-800 transition ease-in-out duration-150"
          >
            Back
          </Link>
        </div>
      </div>
      <div className="py-6 px-4 w-full">
        <form onSubmit={submit}>
          <div className="flex justify-center mb-2">
            <Avatar
              source={data.profile_photo_url}
              name={data.name}
              sizeClass="h-32 w-32"
              textSize="text-4xl"
              bgColor="bg-gray-400"
            />
          </div>
          <div className="block md:flex flex-row">
            <InputLabel
              forInput="profile_photo_url"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Photo"
            />
            <div className="block w-full">
              <FileInput
                type="text"
                name="profile_photo_url"
                className="mt-1 block w-full"
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
              forInput="username"
              className="md:w-1/4 md:self-center md:font-bold"
              value="Username"
            />
            <div className="block w-full">
              <TextInput
                type="text"
                name="username"
                value={data.username}
                className="mt-1 block w-full"
                isFocused={false}
                handleChange={onInputHandleChange}
              />
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
                value={data.profile_gender}
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
          <PrimaryButton className="bg-blue-400" processing={processing}>
            Update
          </PrimaryButton>
        </form>
      </div>
    </AuthenticatedLayout>
  );
}
