import '@testing-library/jest-dom/extend-expect';
import { cleanup, render, screen } from '@testing-library/react';
import React from 'react';
import { act } from 'react-dom/test-utils';
import { MemoryRouter } from 'react-router-dom';
import App from './App';
import api from './services/api';

jest.mock('./services/api');
const mockedApi = api as jest.Mocked<typeof api>;
afterEach(() => {
  cleanup();
  jest.resetAllMocks();
});

const token =
  'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MDUxMzQyNDQsImlzcyI6Imluc3RhZ3JhbS5jbG9uZSIsImlhdCI6MTYwNTA0Nzg0NH0.Rg1iGzoCiAl14BPUJQYjm7n941WNlYBmOqGsaruRPBo';

describe('App rendering & routes', () => {
  const renderWithRouter = async (component: JSX.Element, initialRoute = '/') => {
    return await render(<MemoryRouter initialEntries={[initialRoute]}>{component}</MemoryRouter>);
  };

  it('renders Login component', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 404,
            error: {
              type: 'RESOURCE_NOT_FOUND',
              description: 'Wrong password, try again.',
            },
          },
        ],
      }),
    );
    const { getByText } = await renderWithRouter(<App />);
    expect(getByText(/Log In/i)).toBeInTheDocument();
  });

  test("authentication & Homepage's component rendering", async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 200,
            token: token,
          },
        ],
      }),
    );
    await act(async () => {
      await renderWithRouter(<App />);
      (await screen.findByText(/Log In/i)).click();
    });
    expect(screen.getByText(/Home/i)).toBeInTheDocument();
  });

  it('renders 404 component', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 404,
            error: {
              type: 'RESOURCE_NOT_FOUND',
              description: 'Wrong password, try again.',
            },
          },
        ],
      }),
    );
    await act(async () => {
      await renderWithRouter(<App />, '/404');
    });
    expect(screen.getByText(/Not Found/i)).toBeInTheDocument();
  });

  it('renders Explore component', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 200,
            token: token,
          },
        ],
      }),
    );
    await act(async () => {
      await renderWithRouter(<App />);
      (await screen.findByText(/Log In/i)).click();
      (await screen.findByText(/Explore/i)).click();
    });
    expect(screen.getByText(/Explore/i)).toBeInTheDocument();
  });
});
