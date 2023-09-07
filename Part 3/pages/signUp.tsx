import Head from "next/head";
import Link from "next/link";
import Image from "next/image";
import logo from "../public/transparent_logo.png";
import axios from "axios";
import $ from "jquery";
import { Info } from "lucide-react";
import { useRouter } from "next/router";
import { useCookies } from "react-cookie";

export default function SignUp() {
  const router = useRouter();
  const [cookies, setCookie] = useCookies(["token"]);

  // function to handle form submit
  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    // validate email
    const email = String($("#email").val());
    const emailRegex = /[A-Za-z0-9]+@make-it-all\.co\.uk/i;
    if (!emailRegex.test(email)) {
      $("#email-feedback").removeClass("hidden");
      return;
    } else {
      $("#email-feedback").addClass("hidden");
    }

    // validate username
    const username = String($("#username").val());
    // username must be between 4 and 20 characters, can't start or end with _/., can't include double _/.
    const usernameRegex =
      /^(?=.{4,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/;
    if (!usernameRegex.test(username)) {
      $("#username-feedback").removeClass("hidden");
      return;
    } else {
      $("#username-feedback").addClass("hidden");
    }

    // validate password
    const password = String($("#password").val());
    const confirmPassword = $("#confirm-password").val();
    const passwordRegex =
      /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;

    if (!passwordRegex.test(password)) {
      $("#password-feedback").removeClass("hidden");
      console.log(password);
      return;
    } else {
      $("#password-feedback").addClass("hidden");
    }

    if (password != confirmPassword) {
      $("#confirm-password-feedback").removeClass("hidden");
      return;
    } else {
      $("#confirm-password-feedback").addClass("hidden");
    }

    const payload = {
      email,
      username,
      password,
    };

    // axios post request to sign up api endpoint
    axios
      .post("/api/signup", payload)
      .then((response) => {
        const { token } = response.data;
        console.log(token);
        // set token in local storage
        setCookie("token", token, { path: "/" });
        // redirect to chat
        router.push("/chat");
      })
      .catch((error) => {
        const { message } = error.response.data;
        console.log(message);
      });
  };
  return (
    <>
      <Head>
        <title>Sign up</title>
        <meta name="description" content="Make it all login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/transparent_logo.png" />
      </Head>

      <main className="flex justify-center">
        <div className="h-screen container flex justify-center items-center">
          <div className="border rounded p-8 mx-4 w-full sm:w-3/4 md:w-1/2 ">
            <form onSubmit={handleSubmit}>
              <div className="flex justify-between items-center pb-4">
                <h1 className="text-3xl">Sign-up</h1>
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
                  <span
                    className="label-text tooltip tooltip-bottom"
                    data-tip="Email must be a make-it-all company email"
                  >
                    <Info size={20} className="text-secondary" />
                  </span>
                </label>
                <input
                  type="email"
                  placeholder="Enter Email"
                  className="input input-bordered w-full"
                  id="email"
                />
                <label className="label hidden" id="email-feedback">
                  <span className="label-text-alt text-red-700">
                    Invalid Email
                  </span>
                </label>
              </div>
              <div className="form-control w-full">
                <label className="label">
                  <span className="label-text">Username</span>
                  <span
                    className="label-text tooltip tooltip-bottom"
                    data-tip="Username must be between 4-20 characters. Can't start/end with _ or ."
                  >
                    <Info size={20} className="text-secondary" />
                  </span>
                </label>
                <input
                  type="text"
                  placeholder="Enter Username"
                  className="input input-bordered w-full"
                  id="username"
                />
                <label className="label hidden" id="username-feedback">
                  <span className="label-text-alt text-red-700">
                    Invalid Username
                  </span>
                </label>
              </div>
              <div className="form-control w-full">
                <label className="label">
                  <span className="label-text">Password</span>
                  <span
                    className="label-text tooltip tooltip-bottom"
                    data-tip="Minimum eight characters, at least one letter, one number and one special character"
                  >
                    <Info size={20} className="text-secondary" />
                  </span>
                </label>
                <input
                  type="password"
                  placeholder="Enter Password"
                  className="input input-bordered w-full"
                  id="password"
                />
                <label className="label hidden" id="password-feedback">
                  <span className="label-text-alt text-red-700">
                    Invalid Password
                  </span>
                </label>
              </div>
              <div className="form-control w-full">
                <label className="label">
                  <span className="label-text">Confirm Password</span>
                </label>
                <input
                  type="password"
                  placeholder="Confirm Password"
                  className="input input-bordered w-full"
                  id="confirm-password"
                />
                <label className="label hidden" id="confirm-password-feedback">
                  <span className="label-text-alt text-red-700">
                    Passwords don&apos;t match
                  </span>
                </label>
              </div>
              <button className="btn btn-primary w-full mt-4" type="submit">
                Submit
              </button>
            </form>
            <div className="mt-4 mx-auto flex justify-end">
              Already have an account?&nbsp;
              <Link href={"/login"} className="text-accent">
                Login
              </Link>
            </div>
          </div>
        </div>
      </main>
    </>
  );
}
