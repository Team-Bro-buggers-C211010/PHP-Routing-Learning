import { useForm } from "react-hook-form";
import type z from "zod";
import { userFormSchema } from "../Types/zod-types";
import { zodResolver } from "@hookform/resolvers/zod";
import UsersList from "../component/UsersList";
import axios from "axios";
import type { FormInfo, Users } from "../Types/types";
import { useMutation, useQueryClient } from "@tanstack/react-query";

const Home = () => {

  const {
    register,
    handleSubmit,
    formState: { errors },
    reset,
  } = useForm<z.infer<typeof userFormSchema>>({
    resolver: zodResolver(userFormSchema),
    defaultValues: {
      name: "",
      email: "",
    },
  });

  const createUser = async (data: FormInfo) => {
    const result = await axios.post(`http://localhost:8080/users`, data, { withCredentials: true });
    return result.data;
  };

  const queryClient = useQueryClient();

  const { mutate } = useMutation({
    mutationFn: createUser,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["users"] });
      reset();
      alert("Yessssss....: New user created!");
    },
    onMutate: async (newUser) => {
      await queryClient.cancelQueries({ queryKey: ["users"] });
      const previousUsers = queryClient.getQueryData(["users"]);
      queryClient.setQueryData(["users"], (old: Users[] = []) => [
        ...old,
        { id: Date.now(), ...newUser },
      ]);
      return { previousUsers };
    },

    onError: (error, _variables, context) => {
      alert("Error on creating: " + error.message);
      if (context?.previousUsers) {
        queryClient.setQueryData(["users"], context.previousUsers);
      }
    },
  });

  const onSubmit = (data: z.infer<typeof userFormSchema>) => {
    const callCreateUserApi = async () => {
      try {
        mutate(data);
      } catch (error) {
        if (error instanceof Error) {
          alert("Error on creating: " + error.message);
        }
      }
    };
    callCreateUserApi();
  };
  return (
    <div>
      Hello from user app !!!
      <div className="mt-5">
        <form
          onSubmit={handleSubmit(onSubmit)}
          className="flex flex-col gap-2 max-w-3xl border border-amber-300 p-5 container mx-auto"
        >
          <div>
            <input
              {...register("name", { required: true })}
              type="text"
              placeholder="Give your full name"
              className={`input w-full focus:outline-none focus:ring-0 ${
                errors.name && "border-1 border-red-600"
              }`}
            />
            {errors.name && (
              <div className="text-red-500">{errors.name.message}</div>
            )}
          </div>
          <div>
            <input
              {...register("email", { required: true })}
              type="email"
              placeholder="Enter your email here"
              className={`input w-full focus:outline-none focus:ring-0 ${
                errors.email && "border-1 border-red-600"
              }`}
            />
            {errors.email && (
              <div className="text-red-500">{errors.email.message}</div>
            )}
          </div>
          <button
            className="cursor-pointer btn-outline border-amber-400 btn"
            type="submit"
          >
            Submit now
          </button>
        </form>
      </div>
      <hr className="my-5" />
      <div className="border-2 border-amber-200">
        <UsersList />
      </div>
    </div>
  );
};

export default Home;
