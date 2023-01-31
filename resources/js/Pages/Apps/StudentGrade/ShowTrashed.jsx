import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Link, useForm, usePage } from "@inertiajs/inertia-react";
import InputLabel from "@/Components/Forms/InputLabel";
import TextInput from "@/Components/Forms/TextInput";
import dayjs from "dayjs";
import Avatar from "@/Components/Avatar";
import TextArea from "@/Components/Forms/TextArea";
import { TransformConstants } from "@/Helper/Transform";

export default function ShowTrashed(props) {
  const { _data, _constants } = usePage().props;
  const { data } = useForm({
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
            href={route("settings.trashed.users.index")}
            className="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-800 transition ease-in-out duration-150"
          >
            Back
          </Link>
        </div>
      </div>
      <div className="py-6 px-4 w-full">
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
            className="md:w-1/4 md:self-center md:font-bold"
            forInput="name"
            value="Fullname"
          />
          <TextInput
            className="mt-1 block w-full bg-gray-100"
            value={data.name}
            disabled={true}
          />
        </div>
        <div className="block md:flex flex-row mt-4">
          <InputLabel
            className="md:w-1/4 md:self-center md:font-bold"
            forInput="profile_gender"
            value="Gender"
          />
          <TextInput
            className="mt-1 block w-full bg-gray-100"
            value={data.profile_gender}
            disabled={true}
          />
        </div>
        <div className="block md:flex flex-row mt-4">
          <InputLabel
            className="md:w-1/4 md:self-center md:font-bold"
            forInput="profile_birthday"
            value="Birthday"
          />
          <TextInput
            className="mt-1 block w-full bg-gray-100"
            value={data.profile_birthday}
            disabled={true}
          />
        </div>
        <div className="block md:flex flex-row mt-4">
          <InputLabel
            className="md:w-1/4 md:self-center md:font-bold"
            forInput="profile_phone"
            value="Phone"
          />
          <TextInput
            className="mt-1 block w-full bg-gray-100"
            value={data.profile_phone}
            disabled={true}
          />
        </div>
        <div className="block md:flex flex-row mt-4">
          <InputLabel
            className="md:w-1/4 md:self-center md:font-bold"
            forInput="profile_address"
            value="Address"
          />
          <TextArea
            className="mt-1 block w-full bg-gray-100"
            value={data.profile_address}
            disabled={true}
          />
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
