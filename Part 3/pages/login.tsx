import Head from "next/head";
import Image from "next/image";
import logo from "../public/transparent_logo.png";
import axios from "axios";
import $ from "jquery";
import { AlertCircle } from "lucide-react";
import { useRouter } from "next/router";
import Link from "next/link";
import { useEffect } from "react";
import { verify } from "jsonwebtoken";
import { useCookies } from "react-cookie";

export default function Login() {
  const [cookies, setCookie] = useCookies(["token"]);
  const router = useRouter();
  // function to handle form submit
  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    // hide error alert
    $(".alert").addClass("hidden");
    const payload = {
      email: $("#email").val(),
      password: $("#password").val(),
    };

    // axios post request to login api endpoint
    axios
      .post("/api/login", payload)
      .then((response) => {
        // handle success
        const { token } = response.data;
        // set token in local storage
        setCookie("token", token, { path: "/" });
        // redirect to chat if login successful
        router.push("/chat");
      })
      .catch((error) => {
        // handle error
        const { message } = error.response.data;
        console.log(message);
        if (message == "Invalid email or password") {
          // show error alert
          $(".alert").removeClass("hidden");
        }
      });
  };

  useEffect(() => {
    try {
      const token = String(cookies.token);
      verify(token, "your_jwt_secret");
      router.push("/chat");
    } catch (e) {
      console.log(e);
    }
  });
  return (
    <>
      <Head>
        <title>Login</title>
        <meta name="description" content="Make it all login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/transparent_logo.png" />
      </Head>

      <main className="flex justify-center">
        <div className="h-screen container flex justify-center items-center">
          <div className="border rounded p-8 mx-4 w-full sm:w-3/4 md:w-1/2 ">
            <div className="alert alert-error mb-8 hidden">
              <div>
                <AlertCircle />
                <span>Incorrect Username or Password</span>
              </div>
            </div>
            <form onSubmit={handleSubmit}>
              <div className="flex justify-between items-center pb-4">
                <h1 className="text-3xl">Login</h1>
                <Image
                  className="w-14"
                  src={logo}
                  alt="logo"
                  width={268}
                  height={260}
                />
              </div>
              <div className="form-control w-full">
                <label className="label">
                  <span className="label-text">Email</span>
                </label>
                <input
                  type="email"
                  placeholder="Enter Email"
                  className="input input-bordered w-full"
                  id="email"
                />
              </div>
              <div className="form-control w-full">
                <label className="label">
                  <span className="label-text">Password</span>
                </label>
                <input
                  type="password"
                  placeholder="Enter Password"
                  className="input input-bordered w-full"
                  id="password"
                />
              </div>
              <button className="btn btn-primary w-full mt-6" type="submit">
                Submit
              </button>
            </form>
            <div className="mt-4 mx-auto flex justify-end">
              Don&apos;t have an account?&nbsp;
              <Link href={"/signUp"} className="text-accent">
                Sign-up
              </Link>
            </div>
          </div>
        </div>
      </main>
    </>
  );
}
